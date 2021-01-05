<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version   1.0.0
 * @link       
 */

namespace App\Service\Dao;

use App\Model\Defray;

/**
 * DefrayDAO
 *
 *
 * @package App\Service\Dao
 */
class DefrayDAO
{
    /**
     * @param string $order_no
     *
     * @return mixed
     */
    public function getByOrderNo(string $order_no): ?Defray
    {
        return Defray::where('order_no', $order_no)->first();
    }

    /**
     * @param string $channel
     *
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function getWaitingNotifyOrderByChannel(string $channel)
    {
        return Defray::where('channel', $channel)->where('status', 0)->get();
    }
}