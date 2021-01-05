<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Request\Auth\LoginRequest;
use App\Service\AgentService;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserMemberDAO;
use App\Service\Dao\UserRechargeDAO;
use App\Service\Dao\UserRelationDAO;
use App\Middleware\AgentAuthMiddleware;
use App\Service\Dao\UserTaskDAO;
use App\Service\Dao\UserWithdrawalDAO;
use App\Service\UploadService;

use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * @Middleware(AgentAuthMiddleware::class)
 * @Controller()
 * @package App\Controller\v1
 */
class AgentController extends AbstractController
{
    /**
     * @Inject()
     * @var UploadService
     */
    private $uploadService;

    /**
     * 后台登陆接口
     *
     * @PostMapping(path="login")
     * @param LoginRequest $request
     */
    public function login()
    {
        $add_num  = $this->request->input('add_num', '');
        $account  = trim($this->request->input('username', ''));
        $password = trim($this->request->input('password', ''));
        $code     = trim($this->request->input('code', ''));

        if ($account === '') {
            $this->error('logic.PHONE_NUMBER_ERROR');
        }

        if ($code === '') {
            // $this->error('logic.CODE_ERROR');
        }

        // $user = $this->container->get(AgentService::class)->login($account, $code, $add_num);
        $user = $this->container->get(AgentService::class)->login($account, $password, $add_num);

        // 生成Token
        $token = JwtInstance::instance()->encode($user);

        $this->success([
            'token' => $token
        ]);
    }

    /**
     * @GetMapping(path="info")
     */
    public function info()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $this->success([
            'id'       => $user->id,
            'avatar'   => $user->avatar,
            'email'    => $user->email,
            'nickname' => $user->nickname,
            'phone'    => $user->phone,
            'username' => $user->phone
        ]);
    }

    /**
     * 统计数据
     *
     * @GetMapping(path="statistical_data")
     */
    public function getStatisticalData()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($user_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        $user_count      = count($lower_ids);
        $payment_sum     = $this->container->get(AgentService::class)->getPaymentSumAmount($lower_ids);
        $withdrawal_sum  = $this->container->get(AgentService::class)->getWithdrawalSumAmount($lower_ids);
        $user_income_sum = $this->container->get(AgentService::class)->getUserIncomeSumAmount($lower_ids);

        $this->success([
            'user_count'      => (float)$user_count,
            'payment_sum'     => (float)$payment_sum,
            'withdrawal_sum'  => (float)$withdrawal_sum,
            'user_income_sum' => (float)$user_income_sum
        ]);
    }

    /**
     * 每周数据
     *
     * @GetMapping(path="week_data")
     */
    public function getWeekData()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($user_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        $user_data        = $this->container->get(AgentService::class)->getWeekRegisterUserData($lower_ids);
        $payment_data     = $this->container->get(AgentService::class)->getWeekPaymentData($lower_ids);
        $withdrawal_data  = $this->container->get(AgentService::class)->getWeekWithdrawalData($lower_ids);
        $user_income_data = $this->container->get(AgentService::class)->getWeekUserIncomeData($lower_ids);

        $this->success([
            'user_data'        => $user_data,
            'payment_data'     => $payment_data,
            'withdrawal_data'  => $withdrawal_data,
            'user_income_data' => $user_income_data
        ]);
    }

    /**
     * 获取团队等级列表
     *
     * @GetMapping(path="team_level")
     */
    public function getTeamLevel()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(UserRelationDAO::class)->getTeamLevelByParentId((int)$user_id);

        $this->success($result);
    }

    /**
     * 获取团队列表
     *
     * @GetMapping(path="team")
     */
    public function getTeamList()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $params = $this->request->all();

        $params['parent_id'] = $user_id;

        $result = $this->container->get(UserRelationDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 下级详情
     *
     * @GetMapping(path="lower_detail")
     */
    public function getLowerDetail()
    {
        $lower_id = (int)$this->request->input('user_id', '');

        $result = $this->container->get(AgentService::class)->getLowerDetail($lower_id);

        $this->success($result);
    }

    /**
     * 获取数据列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        if (!isset($params['time']))
            $params['time'] = '';

        if ($params['time'] === '') {
            unset($params['time']);
        }
        else if (is_array($params['time']) && count($params['time']) === 2) {
            $params['time'][0] = strtotime($params['time'][0]);
            $params['time'][1] = strtotime($params['time'][1]);
        }

        $user_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($user_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        if ($params['user_id'] === '' || !isset($params['user_id']) || (!in_array($params['user_id'], $lower_ids) && (int)$params['user_id'] !== $user_id)) {
            $this->error('logic.USER_NOT_FOUND');
        }
        else {
            $team = $this->container->get(UserRelationDAO::class)->getAllLowers((int)$params['user_id'])->toArray();

            $lower_ids = array_column($team, 'user_id');
        }

        $recharge_amount_sum = $this->container->get(UserBillDAO::class)->getAmountSum($params, [6, 8], $lower_ids);

        $withdrawal_amount_sum = $this->container->get(UserWithdrawalDAO::class)->getAmountSum($params, $lower_ids);

        $withdrawal_user_count = $this->container->get(UserWithdrawalDAO::class)->getUserCount($params, $lower_ids);

        $first_recharge_count = $this->container->get(UserBillDAO::class)->getFirstRechargeUserCount($params, $lower_ids);

        $overlay_member_count = count($this->container->get(UserRechargeDAO::class)->getOverLayMemberIds($params, $lower_ids));

        $user_count = $this->container->get(UserDAO::class)->getMemberCountByParams($params, $lower_ids);

        $complete_task_count = $this->container->get(UserTaskDAO::class)->getCompleteUserCount($params, $lower_ids);

        $activity_amount = $this->container->get(UserBillDAO::class)->getAmountSum($params, [11], $lower_ids);

        $d_value = $recharge_amount_sum - $withdrawal_amount_sum;

        $this->success([
            'recharge_amount_sum'   => $recharge_amount_sum,
            'withdrawal_amount_sum' => $withdrawal_amount_sum,
            'withdrawal_user_count' => $withdrawal_user_count,
            'first_recharge_count'  => $first_recharge_count,
            'overlay_member_count'  => $overlay_member_count,
            'user_count'            => $user_count,
            'complete_task_count'   => $complete_task_count,
            'activity_amount'       => $activity_amount,
            'd_value'               => $d_value
        ]);
    }

    /**
     * 查看数据详情
     *
     * @GetMapping(path="detail")
     */
    public function getDetail()
    {
        $type = (int)$this->request->input('type', 1);

        $time = $this->request->input('time', '');

        $perPage = (int)$this->request->input('perPage', 10);

        $user_id = $this->request->input('user_id', '');

        $search_time = [];
        if (is_array($time) && count($time) === 2) {
            $search_time[0] = strtotime($time[0]);
            $search_time[1] = strtotime($time[1]);
        }
        else {
            $search_time = null;
        }

        $result = $this->container->get(AgentService::class)->getDetail($type, $perPage, $user_id, $search_time);

        $this->success($result);
    }

    /**
     * @GetMapping(path="user_member")
     */
    public function checkUserMember()
    {
        $user_id = (int)$this->request->input('user_id', 0);

        $result = $this->container->get(UserMemberDAO::class)->getByUserId($user_id);

        $this->success($result);
    }

    /**
     * @GetMapping(path="bill")
     */
    public function lowersBill()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($user_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        $params = $this->request->all();

        $result = $this->container->get(UserBillDAO::class)->getLowerList($lower_ids, $params);

        $this->success($result);
    }

    /**
     * @GetMapping(path="user_level")
     */
    public function getUserLevel()
    {
        $result = $this->container->get(UserLevelDAO::class)->getList();

        $this->success($result);
    }

    /**
     * 单个文件上传
     *
     * @PostMapping(path="upload")
     */
    public function single()
    {
        if (!$file = $this->request->file('file')) {
            $this->error('logic.PLEASE_SELECT_FILE');
        }

        $result = $this->uploadService->handle($file, '');

        $this->success($result);
    }

    /**
     * 获取团队成员列表
     *
     * @GetMapping(path="team_member")
     */
    public function getTeamMember()
    {
        // 获取当前用户下所有会员
        $user   = JwtInstance::instance()->build()->getUser();
        $result = $this->container->get(UserService::class)->getTeamMember($user->id);

        $this->success($result);
    }
}