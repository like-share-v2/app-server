<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Payment;

/**
 * 支付DAO
 *
 *
 * @package App\Service\Dao
 */
class PaymentDAO extends Base
{
    /**
     * 获取指定用户列表的充值总额
     *
     * @param array $user_ids
     * @return int
     */
    public function getAmountSumByUserIds(array $user_ids)
    {
        return Payment::query()->whereIn('user_id', $user_ids)->where('type', 1)->where('status', 2)->sum('amount');
    }

    /**
     * 添加支付记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?Payment
    {
        return Payment::query()->create($data);
    }

    /**
     * @param string $pay_no
     * @return Payment|null
     */
    public function firstByPayNo(string $pay_no): ?Payment
    {
        return Payment::query()->where('pay_no', $pay_no)->first();
    }
}