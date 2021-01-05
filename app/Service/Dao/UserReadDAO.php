<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserReadRecord;
use Hyperf\Cache\Annotation\Cacheable;

/**
 * 用户已读记录DAO
 *
 *
 * @package App\Service\Dao
 */
class UserReadDAO extends Base
{
    /**
     * 添加已读记录
     *
     * @param int $user_id
     * @param int $notify_id
     * @return mixed
     */
    public function firstOrCreate(int $user_id, int $notify_id)
    {
        return UserReadRecord::query()->firstOrCreate([
            'user_id'   => $user_id,
            'notify_id' => $notify_id
        ]);
    }

    /**
     * 获取用户已读记录
     *
     * @Cacheable(prefix="user_read", ttl=9000, listener="user-read-update")
     * @param int $user_id
     * @return mixed
     */
    public function get(int $user_id)
    {
        return UserReadRecord::query()->where('user_id', $user_id)->get()->toArray();
    }

    /**
     * 删除已读记录
     *
     * @param int $user_id
     * @param int $notify_id
     * @return int|mixed
     */
    public function delete(int $user_id, int $notify_id)
    {
        return UserReadRecord::query()->where('user_id', $user_id)->where('notify_id', $notify_id)->delete();
    }
}