<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserOnlineRecharge;

/**
 * 用户在线充值DAO
 *
 * @package App\Service\Dao
 */
class UserOnlineRechargeDAO extends Base
{
    public function create(array $data)
    {
        return UserOnlineRecharge::query()->create($data);
    }

    /**
     * @param int $payment_id
     * @return mixed
     */
    public function firstByPaymentId(int $payment_id): ?UserOnlineRecharge
    {
        return UserOnlineRecharge::query()->where('payment_id', $payment_id)->first();
    }
}