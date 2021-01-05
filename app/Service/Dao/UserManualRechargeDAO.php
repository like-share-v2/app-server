<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserManualRecharge;

/**
 * 用户手动充值DAO
 *
 *
 * @package App\Service\Dao
 */
class UserManualRechargeDAO extends Base
{
    /**
     * 创建用户手动充值记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserManualRecharge::query()->create($data);
    }

    /**
     * 获取用户扫码充值记录
     *
     * @param int $user_id
     * @return mixed
     */
    public function getListByUserId(int $user_id)
    {
        return UserManualRecharge::query()
            ->with(['userLevel:level,name'])
            ->where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);
    }
}