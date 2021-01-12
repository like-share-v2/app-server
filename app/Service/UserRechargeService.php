<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\Payment\DSEDPay;
use App\Kernel\Payment\GagaPay;
use App\Kernel\Payment\HaodaMallPay;
use App\Kernel\Payment\HZPay;
use App\Kernel\Payment\IPay;
use App\Kernel\Payment\IPayIndia;
use App\Kernel\Payment\JasonBagPay;
use App\Kernel\Payment\LinkPay;
use App\Kernel\Payment\PopModePay;
use App\Kernel\Payment\SeproPay;
use App\Kernel\Payment\ShineUPay;
use App\Kernel\Payment\StepPay;
use App\Kernel\Payment\YT2Pay;
use App\Kernel\Payment\YTPay;
use App\Kernel\Payment\YZPay;
use App\Kernel\Payment\ZFPay;
use App\Kernel\Payment\ZowPay;
use App\Kernel\Utils\JwtInstance;
use App\Model\User;
use App\Model\UserNotifyContent;
use App\Service\Dao\CountryBankDAO;
use App\Service\Dao\CountryDAO;
use App\Service\Dao\LanguageDAO;
use App\Service\Dao\PaymentDAO;
use App\Service\Dao\UserBankRechargeDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserLevelBuyNumDAO;
use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserManualRechargeDAO;
use App\Service\Dao\UserMemberDAO;
use App\Service\Dao\UserNotifyDAO;
use App\Service\Dao\UserOnlineRechargeDAO;
use App\Service\Dao\UserRechargeDAO;

use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\DbConnection\Db;

/**
 * 用户充值服务
 *
 *
 * @package App\Service
 */
class UserRechargeService extends Base
{
    /**
     * 手动充值
     *
     * @param array $params
     */
    public function manual(array $params)
    {
        $user = JwtInstance::instance()->build()->getUser();

        // 判断充值等级
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$user_level || $user_level->level === 0) {
            $this->error('logic.USER_LEVEL_NOT_FOUND');
        }

        // 判断用户等级
        if ($user->level >= (int)$params['level']) {
            $this->error('logic.USER_RECHARGE_LEVEL_ERROR');
        }

        // 创建手动充值记录
        $this->container->get(UserManualRechargeDAO::class)->create([
            'user_id'  => $user->id,
            'level'    => (int)$params['level'],
            'amount'   => $user_level->price,
            'trade_no' => trim($params['trade_no']),
            'image'    => trim($params['image']),
            'status'   => 0
        ]);
    }

    /**
     * 在线充值
     *
     * @param array $params
     *
     * @return array|void
     */
    public function onlineRecharge(array $params)
    {
        $user = JwtInstance::instance()->build()->getUser();

        // 查找会员等级
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$user_level || $user_level->level === 0) {
            $this->error('logic.USER_LEVEL_NOT_FOUND');
        }

        // 判断充值等级 充值等级要大于会员等级
        if ($user->level >= (int)$params['level']) {
            $this->error('logic.USER_RECHARGE_LEVEL_ERROR');
        }

        Db::beginTransaction();
        try {
            // 创建支付订单
            $payment = $this->container->get(PaymentDAO::class)->create([
                'user_id' => $user->id,
                'pay_no'  => 'vip' . $this->snowflake->generate(),
                'amount'  => $user_level->price,
                'type'    => 1,
                'channel' => $params['channel'],
                'status'  => 0
            ]);

            // 创建充值记录
            $this->container->get(UserRechargeDAO::class)->create([
                'user_id'    => $user->id,
                'level'      => $user_level->level,
                'balance'    => $user_level->price,
                'payment_id' => $payment->id,
                'channel'    => 1,
                'status'     => 0
            ]);
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
            return;
        }

        // 返回支付订单参数
        return [
            'pay_no'     => $payment->pay_no,
            'pay_amount' => $payment->amount
        ];
    }

    /**
     * 创建银行卡充值记录
     *
     * @param array $params
     */
    public function bankRecharge(array $params)
    {
        $user_id = JwtInstance::instance()->build()->getId();

        // 判断充值时间是否开放
        $time = getConfig('recharge_time', []);
        $now  = time();
        // 充值时间未开放
        if ($now < strtotime($time[0]) || $now > strtotime($time[1])) {
            $this->error('logic.RECHARGE_NOT_OPEN_AT_NOW');
        }

        // 判断国家
        $country = $this->container->get(CountryDAO::class)->firstById((int)$params['country_id']);
        if (!$country) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        // 判断国家是否有银行卡
        $country_bank = $this->container->get(CountryBankDAO::class)->firstByCountryId($country->id);
        if (!$country_bank) {
            $this->error('logic.COUNTRY_NOT_HAVE_BANK');
        }

        $this->container->get(UserBankRechargeDAO::class)->create([
            'user_id'              => $user_id,
            'country_id'           => $country->id,
            'name'                 => trim($params['name'] ?? ''),
            'bank'                 => trim($params['bank'] ?? ''),
            'bank_name'            => trim($params['bank_name'] ?? ''),
            'amount'               => (int)($params['amount'] ?? 0),
            'remittance'           => (float)($params['remittance'] ?? 0),
            'receive_bank_name'    => $country_bank->bank_name ?? '',
            'receive_bank_account' => $country_bank->bank_account ?? '',
            'receive_bank_address' => $country_bank->bank_address ?? '',
            'status'               => 0,
            'voucher'              => $params['voucher'] ?? ''
        ]);
    }

    /**
     * 充值会员
     *
     * @param array $params
     */
    public function levelRecharge(array $params)
    {
        $user = JwtInstance::instance()->build()->getUser();

        // 判断会员等级
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$user_level) {
            $this->error('logic.USER_LEVEL_NOT_FOUND');
        }

        // 判断用户等级
        /* if ($user->level >= $user_level->level || $user_level->type === 1) {
            $this->error('logic.USER_RECHARGE_LEVEL_ERROR');
        } */

        if ($user_level->level === -1) {
            $this->error('logic.USER_RECHARGE_LEVEL_ERROR');
        }

        // 判断用户余额
        if ($user->balance < $user_level->price) {
            $this->error('logic.USER_BALANCE_NOT_ENOUGH', 10097);
        }

        // 判断最大购买次数
        if ($user_level->max_buy_num > 0) {
            // 获取用户购买次数
            if ($buyNum = $this->container->get(UserLevelBuyNumDAO::class)->get($user->id, $user_level->level) >= $user_level->max_buy_num) {
                $this->error('logic.MEMBER_BUY_NUM_LIMIT');
            }
        }

        Db::beginTransaction();
        try {

            // 添加用户账单
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $user->id,
                'type'           => 10,
                'balance'        => -$user_level->price,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance - $user_level->price,
            ]);

            // 扣除用户余额
            $user->decrement('balance', $user_level->price);

            // 添加支付记录
            $payment = $this->container->get(PaymentDAO::class)->create([
                'user_id' => $user->id,
                'pay_no'  => 'vip' . date('YmdHis') . mt_rand(1000, 9999),
                'amount'  => $user_level->price,
                'type'    => 1,
                'channel' => 'balance',
                'status'  => 2
            ]);

            // 取当前已开通的对应等级
            if ($findLevel = $this->container->get(UserMemberDAO::class)->firstByUserIdLevel($user->id, $user_level->level)) {
                // 判断是否已过期
                if ($findLevel->effective_time < time()) {
                    $findLevel->effective_time = strtotime(date('Y-m-d',time() + $user_level->duration));
                }
                else {
                    // 累加会员时长
                    $findLevel->effective_time = strtotime(date('Y-m-d',$findLevel->effective_time + $user_level->duration));
                }
                $findLevel->save();
            }
            else {
                // 添加用户开通等级
                $this->container->get(UserMemberDAO::class)->create([
                    'user_id'        => $user->id,
                    'level'          => $user_level->level,
                    'effective_time' => strtotime(date('Y-m-d', time() + $user_level->duration))
                ]);
            }

            // 增加购买次数
            $this->container->get(UserLevelBuyNumDAO::class)->update($user->id, $user_level->level);

            // 判断是否为首次充值该等级
            if (!$this->container->get(UserRechargeDAO::class)->checkUserRechargeLevel($user->id, $user_level->level)) {
                // 首次充值上级返利
                $this->addRechargeRebate($user, $user_level->level);
            }

            // 判断用户是否充值过
            if ($this->container->get(UserRechargeDAO::class)->checkUserLastTenSecondRecharge($user->id, $user_level->level)) {
                throw new \Exception('重复充值');
            }

            // 添加用户充值记录
            $this->container->get(UserRechargeDAO::class)->create([
                'user_id'    => $user->id,
                'level'      => $user_level->level,
                'balance'    => $user_level->price,
                'payment_id' => $payment->id,
                'channel'    => 5,
                'status'     => 1
            ]);

            // 添加系统通知
            $user_notify = $this->container->get(UserNotifyDAO::class)->create([
                'type'    => 1,
                'user_id' => $user->id,
                'title'   => 'system_notification',
                'content' => 'Recharge VIP level successfully'
            ]);

            $country_list    = array_column($this->container->get(CountryDAO::class)->get()->toArray(), 'code');
            $level_name_list = $this->container->get(LanguageDAO::class)->getKeyList($user_level->getAttributes()['name']);

            $notify_save_data = [];
            foreach ($country_list as $key => $lang_code) {
                $level_name         = $level_name_list[$lang_code] ?? $user_level->name;
                $notify_save_data[] = ['notify_id' => $user_notify->id,
                                       'locale'    => $lang_code,
                                       'content'   => __('logic.RECHARGE_USER_LEVEL_SUCCESS', ['name' => $level_name], $lang_code)
                ];
            }

            UserNotifyContent::query()->insert($notify_save_data);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }


        // 清除用户已读记录缓存
        $this->eventDispatcher->dispatch(new DeleteListenerEvent('user-read-update', [$user->id]));
    }

    /**
     * 上级返利
     *
     * @param User $user
     * @param int  $user_level
     */
    public function addRechargeRebate(User $user, int $user_level)
    {
        // 获取会员等级列表
        $level_list = $this->container->get(UserLevelDAO::class)->getAllList(['rebate_type' => 1]);
        $level_list = array_column($level_list->toArray(), null, 'level');

        $type = 2;
        // 一级返利
        $p1_user = $this->container->get(UserDAO::class)->getUserById($user->parent_id);

        //        var_dump($p1_rebate = $level_list[$user_level]['recharge_level_rebate']['p_one_rebate']);

        if ($p1_user && ($p1_rebate = $level_list[$user_level]['recharge_level_rebate']['p_one_rebate'] ?? 0) > 0) {
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p1_user->id,
                'type'           => $type,
                'balance'        => $p1_rebate,
                'before_balance' => $p1_user->balance,
                'after_balance'  => $p1_user->balance + $p1_rebate,
                'low_id'         => $user->id
            ]);

            $p1_user->increment('balance', $p1_rebate);
        }

        // 二级返利
        $p2_user = $this->container->get(UserDAO::class)->getUserById($p1_user->parent_id ?? 0);
        if ($p2_user && ($p2_rebate = $level_list[$user_level]['recharge_level_rebate']['p_two_rebate'] ?? 0) > 0) {

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p2_user->id,
                'type'           => $type,
                'balance'        => $p2_rebate,
                'before_balance' => $p2_user->balance,
                'after_balance'  => $p2_user->balance + $p2_rebate,
                'low_id'         => $user->id
            ]);

            $p2_user->increment('balance', $p2_rebate);
        }

        // 三级返利
        $p3_user = $this->container->get(UserDAO::class)->getUserById($p2_user->parent_id ?? 0);
        if ($p3_user && ($p3_rebate = $level_list[$user_level]['recharge_level_rebate']['p_three_rebate'] ?? 0) > 0) {

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p3_user->id,
                'type'           => $type,
                'balance'        => $p3_rebate,
                'before_balance' => $p3_user->balance,
                'after_balance'  => $p3_user->balance + $p3_rebate,
                'low_id'         => $user->id
            ]);

            $p3_user->increment('balance', $p3_rebate);
        }
    }

    /**
     * @param string $channel
     * @param int    $amount
     * @param int    $country_id
     * @param array  $extra
     *
     * @return array
     */
    public function pay(string $channel, int $amount, int $country_id, array $extra)
    {
        $user = JwtInstance::instance()->build()->getUser();
        $date = date('YmdHis');

        try {
            // 创建支付记录
            $payment = $this->container->get(PaymentDAO::class)->create([
                'user_id' => $user->id,
                'pay_no'  => 'recharge' . $date . mt_rand(10000, 99999) . $user->id,
                'amount'  => $amount,
                'type'    => 1,
                'channel' => $channel,
                'status'  => 0
            ]);

            // 创建用户在线充值
            $this->container->get(UserOnlineRechargeDAO::class)->create([
                'user_id'    => $user->id,
                'country_id' => $country_id,
                'payment_id' => $payment->id,
                'amount'     => $amount,
                'channel'    => $channel,
                'status'     => 0
            ]);

            switch ($channel) {
                case 'gagaPay':
                    $result = $this->container->get(GagaPay::class)->pay($payment->pay_no, $amount, [
                        'type' => $extra['type']
                    ]);
                    break;

                case 'iPay':
                    $result = $this->container->get(IPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'linkPay':
                    $result = $this->container->get(LinkPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'ytPay':
                    $result = $this->container->get(YTPay::class)->pay($payment->pay_no, $amount, [
                        'pay_code' => $extra['pay_code']
                    ]);
                    break;

                case 'dsedPay':
                    $result = $this->container->get(DSEDPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'stepPay':
                    $result = $this->container->get(StepPay::class)->pay($payment->pay_no, $amount, [
                        'phone'     => $user->phone,
                        'email'     => $user->email ?? 'admin@qq.com',
                        'firstName' => $user->nickname
                    ]);
                    break;

                case 'iPayIndia':
                    $result = $this->container->get(IPayIndia::class)->pay($payment->pay_no, $amount, [
                        'user_id' => $user->phone
                    ]);
                    break;

                case 'popModePay':
                    $result = $this->container->get(PopModePay::class)->pay($payment->pay_no, $amount, [
                        'name'  => $user->nickname,
                        'phone' => $user->phone,
                        'email' => $user->email ?? 'admin@qq.com'
                    ]);
                    break;

                case 'hzPay':
                    $result = $this->container->get(HZPay::class)->pay($payment->pay_no, $amount, [
                        'name'  => $user->nickname,
                        'phone' => $user->phone,
                        'email' => $user->email ?? 'admin@qq.com'
                    ]);
                    break;

                case 'haodaPay':
                    $result = $this->container->get(HaodaMallPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'yt2Pay':
                    $result = $this->container->get(YT2Pay::class)->pay($payment->pay_no, $amount, [
                        'name'  => $user->nickname,
                        'phone' => $user->phone,
                        'email' => $user->email ?? $user->phone . '@gmail.com'
                    ]);
                    break;

                case 'ShineUPay':
                    $result = $this->container->get(ShineUPay::class)->pay($payment->pay_no, $amount, [
                        'phone' => $user->phone,
                    ]);
                    break;

                case 'JasonBagPay':
                    $result = $this->container->get(JasonBagPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'SeproPay':
                    $result = $this->container->get(SeproPay::class)->pay($payment->pay_no, $amount);
                    break;

                case 'YZPay':
                    $result = $this->container->get(YZPay::class)->pay($payment->pay_no, $amount, [
                        'name'  => $user->nickname,
                        'phone' => $user->phone,
                        'email' => $user->email ?? $user->phone . '@gmail.com'
                    ]);
                    break;

                case 'ZFPay':
                    $result = $this->container->get(ZFPay::class)->pay($payment->pay_no, $amount);
                    break;

                default:
                    throw new LogicException('logic.SERVER_ERROR');
            }
        }
        catch (\Exception $e) {
            var_dump(sprintf('%s, %s:%s', $e->getMessage(), $e->getFile(), $e->getLine()));
            $this->error('logic.SERVER_ERROR');
            return null;
        }

        return $result;
    }
}