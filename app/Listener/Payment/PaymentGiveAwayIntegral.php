<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Listener\Payment;

use App\Event\PaymentEvent;
use App\Listener\AbstractListener;
use App\Model\Payment;
use App\Service\Dao\UserDAO;

use Hyperf\DbConnection\Db;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * 注册后赠送上级积分
 *
 * @Listener()
 *
 * @package App\Listener
 */
class PaymentGiveAwayIntegral extends AbstractListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            PaymentEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     *
     * @param object $event
     */
    public function process(object $event)
    {
        if (!($event instanceof PaymentEvent)) {
            return;
        }
        $payment = $event->payment;
        if ($payment->status !== 2) {
            return;
        }
        // 充值成功后赠送积分
        $toSelfIntegral = (int)($payment->amount * getConfig('RechargeGiveAwayIntegralRate', 0));
        // 充值成功后赠送上级积分
        $toParentIntegral = (int)($payment->amount * getConfig('SubRechargeGiveAwayParentIntegralRate', 0));
        go(function () use ($toSelfIntegral, $toParentIntegral, $payment) {
            if ($toSelfIntegral > 0) {
                $this->container->get(UserDAO::class)->update($payment->user_id, [
                    'integral' => Db::raw('integral+' . $toSelfIntegral)
                ]);
            }
            if ($toParentIntegral > 0) {
                $this->container->get(UserDAO::class)->update($payment->user->parent_id, [
                    'integral' => Db::raw('integral+' . $toParentIntegral)
                ]);
            }
        });
    }
}