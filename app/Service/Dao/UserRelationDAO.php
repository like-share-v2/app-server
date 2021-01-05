<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserRelation;

/**
 * 用户关系DAO
 *
 *
 * @package App\Service\Dao
 */
class UserRelationDAO extends Base
{
    /**
     * 获取下级ID
     *
     * @param int $parent_id
     * @return mixed
     */
    public function getLowers(array $params, int $parent_id)
    {
        $model =  UserRelation::query()->with(['user:id,nickname,avatar,level'])->where('level', '<=', 3)
            ->where('parent_id', $parent_id)->orderByDesc('id');

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->get();
    }

    /**
     * 获取团队人数
     *
     * @param int $parent_id
     * @return int
     */
    public function getLowersCount(int $parent_id)
    {
        return UserRelation::query()->where('parent_id', $parent_id)->where('level', '<=', 3)->count();
    }

    /**
     * 获取所有下级
     *
     * @param int $parent_id
     * @return mixed
     */
    public function getAllLowers(int $parent_id)
    {
        return UserRelation::query()->where('parent_id', $parent_id)->get();
    }

    public function getListByParentIdAndLevel(int $parent_id, int $level)
    {
        return UserRelation::query()->with(['user:id,account,level'])
            ->where('parent_id', $parent_id)
            ->where('level', $level)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);
    }

    /**
     * 通过上级ID等级获取下级数量
     *
     * @param int $parent_id
     * @param int $level
     * @return int
     */
    public function getCountByParentIdAndLevel(int $parent_id, int $level)
    {
        return UserRelation::query()->where('parent_id', $parent_id)->where('level', $level)->count();
    }

    public function getTeamLevelByParentId(int $parent_id)
    {
        return array_column(UserRelation::query()->where('parent_id', $parent_id)->select(['level'])->groupBy('level')->get()->toArray(), 'level', 'level');
    }

    public function get(array $params)
    {
        $model = UserRelation::query()->with(['user']);

        if (isset($params['parent_id']) && $params['parent_id'] !== '') {
            $model->where('parent_id', $params['parent_id']);
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->where('level', $params['level']);
        }

        if (isset($params['user_level']) && $params['user_level'] !== '') {
            $model->whereHas('userMember', function ($query) use ($params) {
                if ($params['user_level'] == -1) {
                    return $query->where('level', $params['user_level']);
                } else {
                    return $query->where('level', $params['user_level'])->where('effective_time', '>', time());
                }
            });
        }

        if (isset($params['user_id']) && $params['user_id'] !== '') {
            $model->where('user_id', $params['user_id']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    public function checkLower(int $parent_id, int $lower_id)
    {
        return UserRelation::query()->where('parent_id', $parent_id)->where('user_id', $lower_id)->exists();
    }
}