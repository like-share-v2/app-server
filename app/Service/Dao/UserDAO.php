<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\User;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;

/**
 * 用户DAO
 *
 *
 * @package App\Service\Dao
 */
class UserDAO extends Base
{
    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @return bool
     */
    public function checkValueIsUsed(string $column, $value): bool
    {
        return User::query()->where($column, $value)->exists();
    }

    /**
     * 创建用户
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::query()->create($data);
    }

    /**
     * 通过账号查找用户
     *
     * @param string $account
     * @return mixed
     */
    public function findByAccount(string $account)
    {
        return User::query()->where('account', $account)->first();
    }

    /**
     * 通过ID获取用户
     *
     * @param int $id
     * @return mixed
     */
    public function getUserById(int $id): ?User
    {
        return User::query()->with(['info', 'userMember'])->where('id', $id)->first();
    }

    /**
     * 获取所有用户
     *
     * @return mixed
     */
    public function getAllUsers()
    {
        return User::query()->get();
    }

    /**
     * 通过手机号获取用户
     *
     * @param string $phone
     * @return mixed
     */
    public function findByPhone(string $phone): ?User
    {
        return User::query()->where('phone', $phone)->first();
    }

    /**
     * 通过IDS获取总余额
     *
     * @param array $ids
     * @return int
     */
    public function getBalanceSumByIds(array $ids)
    {
        return User::query()->whereIn('id', $ids)->sum('balance');
    }

    public function get(array $params, array $lower_ids)
    {
        $model = User::query()->with(['userLevel:level,name', 'info'])->whereIn('id', $lower_ids);

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $model->whereBetween('created_at', [$params['created_at'], $params['created_at']]);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    public function getMemberCountByParams(array $params, array $lower_ids)
    {
        $model = User::query()->whereIn('id', $lower_ids);

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->count();
    }

    public function getIpUserCount(string $ip)
    {
        return User::query()->where('ip', $ip)->count();
    }

    /**
     * 获取有效下级数量
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getActiveSubCount(int $user_id)
    {
        return User::query()->where('parent_id', $user_id)->whereHas('recharge', function (Builder $builder) {
            $builder->where('status', 1);
        }, '>', 0)->count();
    }

    /**
     * 获取下级用户
     *
     * @param array $parent_ids
     *
     * @return \Hyperf\Utils\Collection
     */
    public function getMemberListByParentIds(array $parent_ids)
    {
        return User::whereIn('parent_id', $parent_ids)->select('id', 'nickname', 'phone')->get();
    }

    /**
     * @param int   $user_id
     * @param array $data
     *
     * @return int
     */
    public function update(int $user_id, array $data): int
    {
        return User::where('id', $user_id)->update($data);
    }
}