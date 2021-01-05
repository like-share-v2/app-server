<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version   1.0.0
 * @link       
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Middleware\AuthMiddleware;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserRechargeDAO;
use App\Service\Dao\UserRelationDAO;
use App\Service\Dao\UserWithdrawalDAO;
use App\Service\TeamService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * 团队控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class TeamController extends AbstractController
{
    /**
     * 团队列表
     *
     * @GetMapping(path="")
     */
    public function teamList()
    {
        $level  = (int)$this->request->input('level', 1);
        $params = $this->request->all();
        if (!isset($params['time']))
            $params['time'] = '';

        $params['time'] = explode(',', $params['time']);

        foreach ($params['time'] as $key => $time) {
            if ($time === '') {
                unset($params['time'][$key]);
            }
        }

        if (count($params['time']) === 0) {
            unset($params['time']);
        }
        else if (is_array($params['time']) && count($params['time']) === 2) {
            if (strtotime($params['time'][0]) > strtotime($params['time'][1])) {
                $time_0 = strtotime($params['time'][1]);
                $time_1 = strtotime($params['time'][0]);

                $params['time'][0] = $time_0;
                $params['time'][1] = $time_1;
            }
            else {
                $params['time'][0] = strtotime($params['time'][0]);
                $params['time'][1] = strtotime($params['time'][1]);
            }
        }
        else {
            unset($params['time']);
        }

        // 团队信息
        $team = $this->container->get(TeamService::class)->getTeamList($params);

        $lower_ids = array_column($team, 'user_id');

        // 充值总额
        $recharge_sum = $this->container->get(UserBillDAO::class)->getAmountSum($params, [6, 8], $lower_ids);

        // 提现总额
        $withdrawal_sum = $this->container->get(UserWithdrawalDAO::class)->getAmountSum($params, $lower_ids);

        // 新增用户
        $user_count = $this->container->get(UserDAO::class)->getMemberCountByParams($params, $lower_ids);

        // 团队购买会员数
        $recharge_level_count = $this->container->get(UserRechargeDAO::class)->getRechargeLevelUserCount($params, $lower_ids);


        // 团队人数
        $level_one_count   = 0;
        $level_two_count   = 0;
        $level_three_count = 0;

        $result = [];
        array_map(function ($item) use (&$level_one_count, &$level_two_count, &$level_three_count, $level, &$result) {
            switch ($item['level']) {
                case 1:
                    $level_one_count += 1;
                    break;
                case 2:
                    $level_two_count += 1;
                    break;
                case 3:
                    $level_three_count += 1;
                    break;
            }

            if ($item['level'] === $level) {
                $result[] = $item;
            }
        }, $team);

        /* if (count($result) > 1) {
            foreach ($result as $value) {
                $key_arrays[] = $value['user']['level'];
            }
            array_multisort($key_arrays, SORT_DESC, SORT_NUMERIC, $result);
        } */

        $this->success([
            'level_one_count'      => $level_one_count,
            'level_two_count'      => $level_two_count,
            'level_three_count'    => $level_three_count,
            'total_recharge_sum'   => $recharge_sum,
            'withdrawal_sum'       => $withdrawal_sum,
            'user_count'           => $user_count,
            'recharge_level_count' => $recharge_level_count,
            'team'                 => $result
        ]);
    }

    /**
     * 团队数据
     *
     * @GetMapping(path="data")
     */
    public function getTeamData()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($user_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        // 总余额
        $balance_sum = $this->container->get(UserDAO::class)->getBalanceSumByIds($lower_ids);
        // 充值总额
        $recharge_sum = $this->container->get(UserRechargeDAO::class)->getAmountSumByUserIds($lower_ids);
        // 总提现
        $withdrawal_sum = $this->container->get(UserWithdrawalDAO::class)->getAmountSumByUserIds($lower_ids);

        $this->success([
            // 总余额
            'balance_sum'    => $balance_sum,
            // 充值总额
            'recharge_sum'   => $recharge_sum,
            // 总提现
            'withdrawal_sum' => $withdrawal_sum
        ]);
    }

    /**
     * 获取推广复制内容
     *
     * @GetMapping(path="promotion_copy")
     */
    public function promotionCopy()
    {
        $content = getConfig('promotion_copy', '');

        $user_id = (string)JwtInstance::instance()->build()->getId();

        $content = str_replace('{id}', $user_id, $content);

        $this->success(['content' => $content]);
    }

    /**
     * @GetMapping(path="app_download_url")
     */
    public function getAppDownloadUrl()
    {
        $user_id = (string)JwtInstance::instance()->build()->getId();
        $this->success([
            'download_url' => getConfig('app_download_url', ''),
            'tg_url'       => getConfig('register_url', '') . '?parentId=' . $user_id
        ]);
    }


    /**
     * @GetMapping(path="team_data")
     */
    public function teamData()
    {
        $user = JwtInstance::instance()->build()->getUser();
        // 用户数据
        $user_data = [
            'avatar'   => $user->avatar,
            'nickname' => $user->nickname,
            'balance'  => $user->balance,
            'id'       => $user->id
        ];

        $content = getConfig('promotion_copy', '');

        // 分享链接
        $content = str_replace('{id}', $user->id, $content);

        $team             = $this->container->get(UserRelationDAO::class)->getAllLowers($user->id);
        $level_one_data   = [];
        $level_two_data   = [];
        $level_three_data = [];

        foreach ($team as $lower) {
            switch ($lower->level) {
                case 1:
                    $level_one_data[] = $lower->user_id;
                    break;
                case 2:
                    $level_two_data[] = $lower->user_id;
                    break;
                case 3:
                    $level_three_data[] = $lower->user_id;
                    break;
            }
        }

        // 会员数量总计
        $level_count_data = [
            'level_one_count'   => count($level_one_data),
            'level_two_count'   => count($level_two_data),
            'level_three_count' => count($level_three_data),
        ];

        // 会员佣金统计
        $level_profit_data = [
            'level_one_profit'   => $this->container->get(UserBillDAO::class)->getTeamProfitByLowerIds($user->id, $level_one_data),
            'level_two_profit'   => $this->container->get(UserBillDAO::class)->getTeamProfitByLowerIds($user->id, $level_two_data),
            'level_three_profit' => $this->container->get(UserBillDAO::class)->getTeamProfitByLowerIds($user->id, $level_three_data),
        ];

        $this->success([
            'user_data'         => $user_data,
            'content'           => $content,
            'level_profit_data' => $level_profit_data,
            'level_count_data'  => $level_count_data
        ]);
    }

    /**
     * @GetMapping(path="team")
     */
    public function team()
    {
        $level = (int)$this->request->input('level', 1);

        $user = JwtInstance::instance()->build()->getUser();

        $team             = $this->container->get(UserRelationDAO::class)->getAllLowers($user->id);
        $level_one_data   = [];
        $level_two_data   = [];
        $level_three_data = [];
        foreach ($team as $lower) {
            switch ($lower->level) {
                case 1:
                    $level_one_data[] = $lower->user_id;
                    break;
                case 2:
                    $level_two_data[] = $lower->user_id;
                    break;
                case 3:
                    $level_three_data[] = $lower->user_id;
                    break;
            }
        }

        // 会员数量总计
        $level_count_data = [
            'level_one_count'   => count($level_one_data),
            'level_two_count'   => count($level_two_data),
            'level_three_count' => count($level_three_data),
        ];

        $user_list = $this->container->get(UserRelationDAO::class)->getListByParentIdAndLevel($user->id, $level);

        $this->success([
        ]);
    }

    /**
     * @GetMapping(path="commission_records")
     */
    public function commissionRecords()
    {
        $user = JwtInstance::instance()->build()->getUser();
        // 用户数据
        $user_data = [
            'avatar'   => $user->avatar,
            'nickname' => $user->nickname,
            'balance'  => $user->balance,
            'id'       => $user->id
        ];

        $list = $this->container->get(UserBillDAO::class)->getCommissionRecords($user->id);

        $this->success($list);
    }
}