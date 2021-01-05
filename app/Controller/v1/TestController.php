<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version   1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Payment\IPay;
use App\Kernel\Utils\JwtInstance;
use App\Model\Task;
use App\Model\User;
use App\Model\UserLevel;
use App\Model\UserRecharge;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserNotifyDAO;
use App\Service\Dao\UserReadDAO;
use App\Service\Dao\UserTaskDAO;
use App\Service\UploadService;
use App\Service\UserRechargeService;
use App\Service\UserRelationService;
use App\Middleware\AuthMiddleware;
use App\Service\UserService;

use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Redis\Redis;

/**
 * @AutoController()
 *
 * @package App\Controller\v1
 */
class TestController extends AbstractController
{
    public function test()
    {
        $month_range = [
            Carbon::now()->format('Y-m')
        ];
        for ($i = 1; $i < 6; $i++) {
            $month_range[] = Carbon::now()->subMonths($i)->format('Y-m');
        }
        print_r($month_range);
        $timeBetween      = [
            strtotime($month_range[count($month_range) - 1]),
            Carbon::createFromDate($month_range[0])->lastOfMonth()->getTimestamp() + 86399,
        ];
        print_r($timeBetween);

        $redis = $this->container->get(Redis::class);
        if ($redis->setnx('a', 'b')) {
            var_dump(true);
        }
    }

    public function getTaskProfit()
    {
        $result = $this->container->get(UserBillDAO::class)->getListByUserId(40007, 2);

        $this->success($result);
    }

    public function testUniqueId()
    {
        $uniqueId = substr(base_convert(md5(uniqid(md5((string)microtime(true)), true)), 16, 10), 0, 6);
        $this->success($uniqueId);
    }

    /**
     * @Middleware(AuthMiddleware::class)
     */
    public function testRecharge()
    {
        $params = $this->request->all();
        $level  = (int)($params['level'] ?? 0);

        if (empty($level)) {
            $this->error('logic.USER_LEVEL_NOT_FOUND');
        }

        if (empty(trim($params['channel'] ?? ''))) {
            $this->error('logic.RECHARGE_CHANNEL_ERROR');
        }

        $payment = $this->container->get(UserRechargeService::class)->onlineRecharge($params);

        $this->success($payment);
    }

    /**
     * @Middleware(AuthMiddleware::class)
     */
    public function testSearch()
    {
        $user_id = JwtInstance::instance()->getId();

        $title = $this->request->input('title', '');

        $received_ids = $this->container->get(UserTaskDAO::class)->getUserReceivedIds($user_id);

        $list = Task::query()
            ->withCount(['userTask' => function ($query) {
                $query->whereIn('status', [0, 1, 2]);
            }])
            ->whereNotIn('id', $received_ids)
            ->where('title', 'like', '%' . trim($title) . '%')
            ->where('status', 1)
            ->orderByDesc('sort')
            ->paginate(10)
            ->toArray();

        $data = array_map(function ($item) {
            $item['remaining_quantity'] = $item['num'] - $item['user_task_count'];
            return $item;
        }, $list['data']);

        $list['data'] = $data;

        $this->success($list);
    }

    /**
     * @Middleware(AuthMiddleware::class)
     */
    public function setAllRead()
    {
        $type = (int)$this->request->input('type', 1);

        $user_id = JwtInstance::instance()->build()->getId();

        $read_ids = array_column($this->container->get(UserReadDAO::class)->get($user_id), 'notify_id');

        // 剩余未读消息ID
        $notify_ids = $this->container->get(UserNotifyDAO::class)->getIdsByUserId($user_id, $type, $read_ids);

        $insert_data = [];

        foreach ($notify_ids as $notify_id) {
            $insert_data[] = ['user_id' => $user_id, 'notify_id' => $notify_id];
        }

        Db::table('user_read_record')->insert($insert_data);

        // 清除缓存
        $this->flushCache('user-read-update', [$user_id]);

        $this->success($notify_ids);
    }

    /**
     * @Middleware(AuthMiddleware::class)
     */
    public function getUserReadRecord()
    {
        var_dump(123);
        $user_id = JwtInstance::instance()->build()->getId();

        // $read_records = $this->container->get(UserReadDAO::class)->get($user_id);

        $result = $this->cache->delete('c:user_read:40007');

        $this->success($result);

        //        $this->success($read_records);
    }

    public function testRegister()
    {
        $this->container->get(UserService::class)->register([
            'invitation_code' => $this->request->input('parent_id', 40013),
            'account'         => mt_rand(100000, 999999),
            'password'        => '928674263',
            'phone'           => $this->request->input('phone', ''),
        ]);
    }

    public function getBankList()
    {
        $bank_list = getConfig('withdrawal_bank', []);
        $result    = array_values($bank_list);
        $this->success($result);
    }

    public function createUserMember()
    {
        $user = User::query()->get()->toArray();

        $userLevel = array_column(UserLevel::query()->get()->toArray(), 'duration', 'level');

        $save_data = [];
        foreach ($user as $value) {
            $save_data[] = ['user_id' => $value['id'], 'level' => -1, 'created_at' => strtotime($value['created_at']), 'effective_time' => -1];
        }

        $recharge_list = UserRecharge::query()->get()->toArray();
        foreach ($recharge_list as $recharge) {
            $save_data[] = [
                'user_id'        => $recharge['user_id'],
                'level'          => $recharge['level'],
                'created_at'     => strtotime($recharge['recharge_time']),
                'effective_time' => strtotime($recharge['recharge_time']) + $userLevel[$recharge['level']]
            ];
        }

        Db::table('user_member')->insert($save_data);
    }

    public function getRechargeTime()
    {
        $time = getConfig('recharge_time', '');

        var_dump(strtotime($time[0]));
        var_dump(strtotime($time[1]));

        $this->success($time);
    }

    public function getWithdrawalTime()
    {
        $time = getConfig('withdrawal_time', '');

        $this->success($time);
    }
}