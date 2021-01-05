<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserInfo;

/**
 * 用户信息DAO
 *
 *
 * @package App\Service\Dao
 */
class UserInfoDAO extends Base
{
    /**
     * 添加用户信息
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserInfo::query()->create($data);
    }

    /**
     * 更新用户信息
     *
     * @param int $user_id
     * @param array $data
     * @return int
     */
    public function update(int $user_id, array $data)
    {
        return UserInfo::query()->where('user_id', $user_id)->update($data);
    }

    /**
     * 检查字段是否存在
     *
     * @param int $user_id
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function checkColumnExisted(int $user_id, string $column, string $value)
    {
        return UserInfo::query()->where('user_id', '<>', $user_id)->where($column, $value)->exists();
    }
}