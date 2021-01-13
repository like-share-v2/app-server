<?php

declare(strict_types=1);

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserMember;

/**
 * 用户会员DAO
 *
 * @package App\Service\Dao
 */
class UserMemberDAO extends Base
{
    /**
     * 添加用户会员
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserMember::query()->create($data);
    }

    /**
     * 添加或更新用户会员等级
     *
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $data)
    {
        return UserMember::query()->updateOrCreate([
            'user_id' => $data['user_id'],
            'level' => $data['level']
        ], [
            'effective_time' => $data['effective_time']
        ]);
    }

    /**
     * 通过用户ID和等级查询用户会员
     *
     * @param int $user_id
     * @param int $level
     * @return mixed
     */
    public function firstByUserIdLevel(int $user_id, int $level): ?UserMember
    {
        return UserMember::query()->where('user_id', $user_id)->where('level', $level)->first();
    }

    /**
     * 通过用户ID获取列表
     *
     * @param int $user_id
     *
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function getByUserId(int $user_id)
    {
        return UserMember::query()->with(['userLevel'])->where('user_id', $user_id)->get();
    }

    /**
     * @param int $user_id
     * @param int $level
     *
     * @return false|int|mixed
     */
    public function deleteMember(int $user_id, int $level)
    {
        return UserMember::where('user_id', $user_id)->where('level', $level)->delete();
    }
}