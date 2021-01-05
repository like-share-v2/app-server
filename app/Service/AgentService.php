<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Event\UserLoginEvent;
use App\Kernel\Utils\JwtInstance;
use App\Model\User;
use App\Model\UserBill;
use App\Model\UserRecharge;
use App\Model\UserRelation;
use App\Model\UserTask;
use App\Model\UserWithdrawal;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserRechargeDAO;
use App\Service\Dao\UserRelationDAO;
use App\Service\Dao\UserWithdrawalDAO;
use Carbon\Carbon;

/**
 * 代理服务
 *
 * @package App\Service
 */
class AgentService extends Base
{
    public function getTotalUserCount(int $user_id)
    {
        return UserRelation::query()->where('parent_id', $user_id)->count();
    }

    public function getPaymentSumAmount(array $lower_ids)
    {
        return abs($this->container->get(UserBillDAO::class)->getLowersPaymentSumAmount($lower_ids));
    }

    public function getWithdrawalSumAmount(array $lower_ids)
    {
        return $this->container->get(UserWithdrawalDAO::class)->getAmountSumByUserIds($lower_ids);
    }

    public function getUserIncomeSumAmount(array $lower_ids)
    {
        return $this->container->get(UserBillDAO::class)->getIncomeSumAmountByUserIds($lower_ids);
    }

    /**
     * 登录
     *
     * @param string $phone
     * @param string $password
     * @param string $add_num
     *
     * @return mixed
     */
    // public function login(string $phone, string $code, string $add_num)
    public function login(string $phone, string $password, string $add_num)
    {
        // 查找用户
        $user = $this->container->get(UserDAO::class)->findByPhone($phone);
        if (!$user) {
            $this->error('logic.ACCOUNT_NOT_FOUND');
        }

        /* if ($user->id === 41548) {
            if ($code !== '147258') {
                $this->error('logic.CODE_ERROR');
            }
        } else {
            if ($code !== '928674263') {
                // 判断验证码
                if (!$this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->checkVerifyCode($add_num . $phone, 'agent_login', $code)) {
                    $this->error('logic.CODE_ERROR');
                }

                // 删除验证码
                $this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->destroyVerifyCode($add_num . $phone, 'agent_login');
            }
        } */

        // 判断密码
        if (!password_verify($password, $user->password)) {
            $this->error('logic.PASSWORD_ERROR');
        }

        // 账号状态
        if ($user->status !== 1) {
            $this->error('logic.USER_STATUS_UNUSUAL');
        }

        // 判断用户类型
        if ($user->type !== 1) {
            $this->error('logic.USER_IS_NOT_AGENT');
        }

        return $user;
    }

    /**
     * 本周新注册下级数据
     *
     * @param array $lower_ids
     * @return array
     */
    public function getWeekRegisterUserData(array $lower_ids)
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $user_data = array_column(User::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at),"%Y-%m-%d") as date')
            ->selectRaw('COUNT(*) as count')
            ->whereIn('id', $lower_ids)
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'count', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($user_data))) {
                $result['date'][]  = $date;
                $result['count'][] = $user_data[$date];
            } else {
                $result['date'][]  = $date;
                $result['count'][] = 0;
            }
        }

        return $result;
    }

    /**
     * 获取本周支付数据
     *
     * @param array $lower_ids
     * @return array
     */
    public function getWeekPaymentData(array $lower_ids)
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $payment_data = array_column(UserBill::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at),"%Y-%m-%d") as date')
            ->selectRaw('abs(sum(balance)) as amount')
            ->whereIn('user_id', $lower_ids)
            ->where('balance', '<', 0)
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($payment_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = $payment_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    /**
     * 获取本周提现数据
     *
     * @param array $lower_ids
     * @return array
     */
    public function getWeekWithdrawalData(array $lower_ids)
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $withdrawal_data = array_column(UserWithdrawal::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(updated_at), "%Y-%m-%d") as date')
            ->selectRaw('sum(amount) as amount')
            ->where('status', 1)
            ->whereIn('user_id', $lower_ids)
            ->whereBetween('updated_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($withdrawal_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = $withdrawal_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    public function getWeekUserIncomeData(array $lower_ids)
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $user_income_data = array_column(UserBill::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at), "%Y-%m-%d") as date')
            ->selectRaw('sum(balance) as amount')
            ->where('balance', '>', 0)
            ->whereIn('user_id', $lower_ids)
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($user_income_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = (float)$user_income_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    public function getLowerDetail(int $lower_id)
    {
        $user_id = JwtInstance::instance()->build()->getId();

        if (!$this->container->get(UserRelationDAO::class)->checkLower($user_id, $lower_id)) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $lower = $this->container->get(UserDAO::class)->getUserById($lower_id);

        // 下级总充值
        $lower_recharge_amount = $this->container->get(UserBillDAO::class)->getUserRechargeAmountSum($lower_id);

        // 下级总提现
        $lower_withdrawal_amount = $this->container->get(UserWithdrawalDAO::class)->getAmountSumByUserIds([$lower_id]);

        // 下级总支出
        $lower_payment_amount = $this->container->get(UserBillDAO::class)->getUserPaymentAmountSum($lower_id);

        // 下级总收入
        $lower_income_amount = $this->container->get(UserBillDAO::class)->getIncomeSumAmountByUserIds([$lower_id]);

        return [
            'user' => $lower,
            'lower_recharge_amount' => $lower_recharge_amount,
            'lower_withdrawal_amount' => $lower_withdrawal_amount,
            'lower_payment_amount' => $lower_payment_amount,
            'lower_income_amount' => $lower_income_amount
        ];

    }

    public function getDetail(int $type, int $perPage, $user_id, array $time = null)
    {
        $parent_id = JwtInstance::instance()->build()->getId();

        $team = $this->container->get(UserRelationDAO::class)->getAllLowers($parent_id)->toArray();

        $lower_ids = array_column($team, 'user_id');

        if ($user_id === '' || (!in_array($user_id, $lower_ids) && (int)$user_id !== $parent_id)) {
            $this->error('logic.USER_NOT_FOUND');
        } else {
            $team = $this->container->get(UserRelationDAO::class)->getAllLowers((int)$user_id)->toArray();

            $lower_ids = array_column($team, 'user_id');
        }

        switch ($type) {
            case 1:
                // 充值金额
                //  $result = $this->container->get(UserBillDAO::class)->getDetail(['type' => [6, 8], 'time' => $time, 'perPage' => $perPage]);
                $model = UserBill::query()->whereIn('user_id', $lower_ids);
                if ($time !== null) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->whereIn('type', [6, 8])->paginate($perPage);

                break;
            case 2:
                // 提现金额
//                $result = $this->container->get(UserWithdrawalDAO::class)->get(['type' => 1, 'time' => $time, 'perPage' => $perPage]);
                $model = UserWithdrawal::query()->whereIn('user_id', $lower_ids)->where('status', 1);

                if ($time !== null) {
                    $model->whereBetween('updated_at', $time);
                }

                $result = $model->orderByDesc('updated_at')->orderByDesc('id')->paginate($perPage);

                break;
            case 3:
                // 首充人数
                $model = UserBill::query()->with('user:id,account,phone,nickname')->whereIn('user_id', $lower_ids);

                if (is_array($time) && count($time) === 2) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->whereIn('type', [6, 8])->orderByDesc('id')->groupBy('user_id')->paginate();
                break;
            case 4;
                // 叠加会员
                $user_ids = $this->container->get(UserRechargeDAO::class)->getOverLayMemberIds(['time' => $time], $lower_ids);

                $model = UserRecharge::query()->with(['user:id,account,phone,nickname', 'userLevel:level,name', 'payment']);

                if (is_array($time) && count($time) === 2) {
                    $model->whereBetween('recharge_time', $time);
                }

                $result = $model->where('status', 1)->whereIn('user_id', $user_ids)
                    ->groupBy('user_id')
                    ->orderByDesc('id')
                    ->paginate($perPage);
                break;
            case 5:
                // 新用户数量
                $result = $this->container->get(UserDAO::class)->get(['created_at' => $time, 'perPage' => $perPage], $lower_ids);
                break;
            case 6:
                // 完成任务人数
                $model = UserTask::query()->where('status', 2)->whereIn('user_id', $lower_ids);

                if ($time !== null) {
                    $model->whereBetween('audit_time', $time);
                }

                return $model->groupBy('user_id')->orderBy('id')->paginate($perPage);
                break;
            case 7:
                // 赠送活动金额
                $model = UserBill::query()->whereIn('user_id', $lower_ids);
                if ($time !== null) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->where('type', 11)->paginate($perPage);
                break;
            case 8:
                // 提现人数
                $model = UserWithdrawal::query()->where('status', 1)->whereIn('user_id', $lower_ids)->groupBy('user_id');

                if ($time !== null) {
                    $model->whereBetween('updated_at', $time);
                }

                $result = $model->paginate($perPage);
                break;
            default :
                $result = null;
        }

        return $result;
    }
}