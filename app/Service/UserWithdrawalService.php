<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Model\UserWithdrawal;
use App\Model\WithdrawalRule1;
use App\Service\Dao\CountryDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserTaskDAO;
use App\Service\Dao\UserWithdrawalDAO;

use Hyperf\DbConnection\Db;

/**
 * 用户提现服务
 *
 *
 * @package App\Service
 */
class UserWithdrawalService extends Base
{
    /**
     * 用户提现
     *
     * @param int    $amount
     * @param int    $country_id
     * @param string $trade_pass
     */
    public function withdrawal(int $amount, $country_id, string $trade_pass)
    {
        // 判断提现时间是否开放
        $time = getConfig('withdrawal_time', []);
        $now  = time();
        // 提现时间未开放
        if ($now < strtotime($time[0]) || $now > strtotime($time[1])) {
            $this->error('logic.WITHDRAWAL_NOT_OPEN_AT_NOW', 400, [
                'start' => $time[0],
                'end'   => $time[1],
            ]);
        }

        // 判断提现金额
        if ($amount === 0) {
            $this->error('logic.PLEASE_INPUT_WITHDRAWAL_AMOUNT');
        }

        // 判断国家ID
        if ($country_id === '' || !is_numeric($country_id)) {
            $this->error('logic.PLEASE_SELECT_COUNTRY');
        }

        // 判断国家
        $country = $this->container->get(CountryDAO::class)->firstById((int)$country_id);
        if (!$country) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $user = JwtInstance::instance()->build()->getUser();

        // 判断是否完成个人资料
        if (empty($user->info->name) || empty($user->info->account)) {
            $this->error('logic.INCOMPLETE_PERSONAL_DATA');
        }
        // 判断积分是否足够
        $integral = (int)(getConfig('withdrawalIntegralRate', 0) * $amount);
        if ($user->integral < $integral) {
            $this->error('logic.WITHDRAWAL_INTEGRAL_INSUFFICIENT', 400, [
                'integral' => $integral
            ]);
        }

        // 判断用户是否完成任务
        if (getConfig('withdrawalMustTaskCompleted', false) && $this->container->get(UserTaskDAO::class)->getUserCompleteTaskCount($user->id) <= 0) {
            $this->error('logic.WITHDRAWAL_MUST_COMPLETE_TASK');
        }

        // 判断提现密码
        if ($user->trade_pass !== null && !password_verify($trade_pass, $user->trade_pass)) {
            $this->error('logic.TRADE_PASS_ERROR');
        }

        // 判断用户余额是否足够
        if ($user->balance < $amount) {
            $this->error('logic.USER_BALANCE_NOT_ENOUGH');
        }

        // 判断是否有正在处理中的提现
        if (UserWithdrawal::query()->where('user_id', $user->id)->where('status', 0)->count() > 0) {
            $this->error('logic.EXIST_PENDING_WITHDRAWAL_APPLY');
        }

        // 提现最低限制
        $low_limit = getConfig('withdrawal_low_limit', 0);
        if ($amount < $low_limit) {
            $this->error(__('logic.WITHDRAWAL_LOW_LIMIT_TIP', ['amount' => $low_limit]));
        }

        // 提现最高限制
        $high_limit = getConfig('withdrawal_high_limit', 0);
        if ($high_limit !== 0 && $amount > $high_limit) {
            $this->error(__('logic.WITHDRAWAL_HIGH_LIMIT_TIP', ['amount' => $high_limit]));
        }

        // 提现次数限制
        $day_count_limit = getConfig('day_withdrawal_count', 0);
        if ($day_count_limit !== 0 && $this->container->get(UserWithdrawalDAO::class)->getTodayUserWithdrawalCount($user->id) >= $day_count_limit) {
            $this->error(__('logic.DAY_WITHDRAWAL_COUNT_TIP', ['count' => $day_count_limit]));
        }

        // 判断本月提现次数
        $month_withdrawal_count = $this->container->get(UserWithdrawalDAO::class)->getMonthWithdrawalCount($user->id);
        $free_count             = getConfig('month_withdrawal_free_count', 0);
        if ($month_withdrawal_count >= $free_count) {
            $service_charge = getConfig('withdrawal_service_charge', 0);
        }
        else {
            $service_charge = 0;
        }

        // 满X有效下级有X次提现机会
        if (getConfig('withdrawal_rule_1_enable', false)) {
            // 获取当前有效下级数量
            $activeSubCount = $this->container->get(UserDAO::class)->getActiveSubCount($user->id);
            /** @var WithdrawalRule1 $findRule */
            if (!$findRule = WithdrawalRule1::query()
                ->where('active_sub', '<=', $activeSubCount)
                ->orderByDesc('withdrawal_count')
                ->where('is_enable', 1)
                ->first()
            ) {
                $this->error('logic.NOT_WITHDRAWABLE');
            }
            $withdrawalCount = $this->container->get(UserWithdrawalDAO::class)->getUserWithdrawalCount($user->id);
            if ($withdrawalCount >= $findRule->withdrawal_count) {
                $this->error(__('logic.WITHDRAWAL_LIMIT', [
                    'count' => $findRule->withdrawal_count
                ]));
            }
        }

        Db::beginTransaction();
        try {
            // 创建用户提现记录
            $this->container->get(UserWithdrawalDAO::class)->create([
                'country_id'     => $country->id,
                'user_id'        => $user->id,
                'amount'         => $amount,
                'service_charge' => $service_charge,
                'status'         => 0,
                'name'           => $user->info->name,
                'account'        => $user->info->account,
                'bank_code'      => $user->info->bank_code,
                'bank_name'      => $user->info->bank_name,
                'email'          => $user->info->email,
                'phone'          => $user->info->phone,
                'upi'            => $user->info->upi,
                'ifsc'           => $user->info->ifsc,
                'integral'       => $integral
            ]);

            // 创建用户账单
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $user->id,
                'type'           => 4,
                'balance'        => -$amount,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance - $amount
            ]);
            // 减少用户余额
            $user->decrement('balance', $amount);
            // 减少用户积分
            if ($integral > 0) {
                $user->decrement('integral', $integral);
            }

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('withdrawal')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}