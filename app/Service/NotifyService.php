<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Event\PaymentEvent;
use App\Exception\LogicException;
use App\Service\Dao\DefrayDAO;
use App\Service\Dao\PaymentDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserNotifyDAO;
use App\Service\Dao\UserOnlineRechargeDAO;

use Hyperf\DbConnection\Db;

/**
 * 支付回调服务
 *
 * @package App\Service
 */
class NotifyService extends Base
{
    public function handlePayout(string $order_no, int $status, float $amount = 0, string $remark = '')
    {
        if (!$defer = $this->container->get(DefrayDAO::class)->getByOrderNo($order_no)) {
            throw new LogicException('logic.ORDER_NOT_FOUND');
        }
        if ($defer->status !== 0) {
            return;
        }
        Db::beginTransaction();
        try {
            switch ($status) {
                case 1: // 失败
                    $defer->status = 2;
                    $defer->save();

                    if ($defer->withdrawal) {
                        // 打款失败
                        $defer->withdrawal->status = 4;
                        $defer->withdrawal->remark = $remark;
                        $defer->withdrawal->save();
                        // 记录退款账单
                        $this->container->get(UserBillDAO::class)->create([
                            'user_id'        => $defer->withdrawal->user->id,
                            'type'           => 5,
                            'balance'        => $defer->withdrawal->amount,
                            'before_balance' => $defer->withdrawal->user->balance,
                            'after_balance'  => $defer->withdrawal->user->balance + $defer->withdrawal->amount
                        ]);
                        // 退还
                        $this->container->get(UserDAO::class)->update($defer->withdrawal->user_id, [
                            'balance'  => Db::raw('balance+' . $defer->withdrawal->amount),
                            'integral' => Db::raw('integral+' . $defer->withdrawal->integral),
                        ]);
                        // 添加用户通知
                        $this->container->get(UserNotifyDAO::class)->create([
                            'type'    => 1,
                            'user_id' => $defer->withdrawal->user->id,
                            'title'   => 'system_notification',
                            'content' => 'Withdrawal failed'
                        ]);
                    }
                    break;

                case 2: // 成功
                    $defer->status = 1;
                    $defer->save();

                    if ($defer->withdrawal) {
                        $defer->withdrawal->status = 1;
                        $defer->withdrawal->save();
                        // 添加用户通知
                        $this->container->get(UserNotifyDAO::class)->create([
                            'type'    => 1,
                            'user_id' => $defer->withdrawal->user_id,
                            'title'   => 'system_notification',
                            'content' => 'Withdrawal passed'
                        ]);
                    }
                    break;

                default:
            }

            Db::commit();
        }
        catch (\Throwable $e) {
            Db::rollBack();
            $this->logger('payment')->error($e->getMessage());
        }
    }

    public function handle(string $pay_no, int $status, string $trade_no, string $remark = '')
    {
        Db::beginTransaction();
        try {
            // 查找支付订单
            if (!$payment = $this->container->get(PaymentDAO::class)->firstByPayNo($pay_no)) {
                throw new LogicException('订单号不存在');
            }
            if (in_array($payment->status, [0, 1])) {
                // 查找用户在线充值订单
                if (!$online_recharge = $this->container->get(UserOnlineRechargeDAO::class)->firstByPaymentId($payment->id)) {
                    throw new LogicException('充值订单不存在');
                }
                // 查找用户
                if (!$user = $this->container->get(UserDAO::class)->getUserById($online_recharge->user_id)) {
                    throw new LogicException('充值用户不存在');
                }
                switch ($status) {
                    case 1: // 支付处理中
                        $payment->status = 1;
                        $payment->save();
                        break;

                    case 2: // 支付成功
                        $payment->status      = 2;
                        $payment->result_desc = $remark;
                        $payment->save();

                        // 修改在线充值订单状态
                        $online_recharge->status = 1;
                        $online_recharge->save();

                        // 创建用户账单
                        $this->container->get(UserBillDAO::class)->create([
                            'user_id'        => $user->id,
                            'type'           => 8,
                            'balance'        => $online_recharge->amount,
                            'before_balance' => $user->balance,
                            'after_balance'  => $user->balance + $online_recharge->amount
                        ]);

                        // 添加用户余额
                        $user->increment('balance', $online_recharge->amount);
                        break;

                    case 3: // 支付失败
                        $payment->status      = 3;
                        $payment->result_desc = $remark;
                        $payment->save();
                        break;

                    default:
                }
            }
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('gaga_pay')->error($e->getMessage());
            throw new LogicException($e->getMessage());
        }
        // 充值事件
        $this->eventDispatcher->dispatch(new PaymentEvent($payment));
    }
}