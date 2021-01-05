<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Event;

use App\Model\Order;

use Hyperf\Di\Annotation\Inject;

/**
 * 订单状态变更事件
 *
 *
 * @package App\Event
 */
class OrderStatusEvent
{
    /**
     * @Inject()
     * @var Order
     */
    public $order;

    /**
     * OrderStatusEvent constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}