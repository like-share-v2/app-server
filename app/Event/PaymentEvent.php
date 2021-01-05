<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Event;

use App\Model\Payment;

/**
 * PaymentEvent
 *
 *
 * @package App\Event
 */
class PaymentEvent
{
    /**
     * @var Payment
     */
    public $payment;

    /**
     * PaymentEvent constructor.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}