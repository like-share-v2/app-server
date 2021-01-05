<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserSignInRecord;

/**
 * 用户签到记录DAO
 *
 *
 * @package App\Service\Dao
 */
class UserSignInRecordDAO extends Base
{
    /**
     * 创建用户签到记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserSignInRecord::query()->create($data);
    }

    /**
     * 检查用户今日是否签到
     *
     * @param int $user_id
     * @return bool
     */
    public function checkUserTodaySign(int $user_id)
    {
        return UserSignInRecord::query()->where('user_id', $user_id)->where('created_at', '>', strtotime(date('Y-m-d')))->exists();
    }
}