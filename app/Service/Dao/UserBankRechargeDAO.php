<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserBankRecharge;

/**
 * 银行卡充值DAO
 *
 * @package App\Service\Dao
 */
class UserBankRechargeDAO extends Base
{
    /**
     * 创建用户银行卡充值记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserBankRecharge::query()->create($data);
    }

    /**
     * 通过用户ID获取用户银行卡充值记录
     *
     * @param int $user_id
     * @return mixed
     */
    public function getListByUserId(int $user_id)
    {
        return UserBankRecharge::query()
            ->with(['userLevel:level,name'])
            ->where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);
    }
}