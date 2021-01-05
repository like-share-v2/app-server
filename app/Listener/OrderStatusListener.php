<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Listener;

use App\Event\OrderStatusEvent;
use App\Model\Order;

use App\Service\OrderService;
use App\Service\SubscribeMessageService;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\DbConnection\Db;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * 订单状态监听器
 *
 * @Listener()
 *
 * @package App\Listener
 */
class OrderStatusListener extends AbstractListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            OrderStatusEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     * @param object $event
     */
    public function process(object $event)
    {
        if (!($event->order instanceof Order)) {
            return;
        }
        $order = $event->order;
        $logger = $this->container->get(LoggerFactory::class)->get('log', 'robot');

        // 清理订单缓存
        $this->container->get(EventDispatcherInterface::class)->dispatch(new DeleteListenerEvent('OrderUpdate', [$order->order_no]));

        switch ($order->status) {
            case 1: // 出发
                $logger->info($order->order_no . ': 已出发');
                // 通知用户机器人已触发...
                break;

            case 2: // 到达
                $logger->info($order->order_no . ': 已到达');
                // 通知用户机器人已到达
                // 发送上门服务提醒
                $this->container->get(SubscribeMessageService::class)->sendServiceReminder($order);
                break;

            case 3: // 完成
                $logger->info($order->order_no . ': 订单完成');
                break;

            case 4: // 异常
                // 通知用户机器人异常...
                // 执行退款逻辑
                $logger->info($order->order_no . ': 机器人异常');
                break;

            case 5: // 订单取消
                break;
            default:
        }
    }
}