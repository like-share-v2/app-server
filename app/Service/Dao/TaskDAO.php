<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\Task;

/**
 * 任务DAO
 *
 *
 * @package App\Service\Dao
 */
class TaskDAO extends Base
{
    /**
     * 获取任务列表
     *
     * @param array $params
     * @param array $user_received_ids
     * @return mixed
     */
    public function getList(array $params, array $user_received_ids)
    {
        $model = Task::query()->with(['category:id,icon', 'levelInfo:level,name'])->withCount(['userTask' => function ($query) {
            $query->whereIn('status', [0, 1, 2]);
        }]);

        if (isset($params['category_id']) && $params['category_id'] !== '') {
            $model->where('category_id', (int)$params['category_id']);
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->where('level', (int)$params['level']);
        }

        if (isset($params['title']) && $params['title'] !== '') {
            $model->where('title', 'like', '%' . trim($params['title']) . '%');
        }

        // 剔除掉用户领取的任务或已完成的任务
        if (is_array($user_received_ids) && count($user_received_ids) > 0) {
            $model->whereNotIn('id', $user_received_ids);
        }

        return $model->where('status', 1)->orderByDesc('amount')->orderByDesc('sort')->orderByDesc('id')->paginate(10);
    }

    /**
     * 获取发布任务列表
     *
     * @param int $user_id
     * @return mixed
     */
    public function getPublishList(int $user_id)
    {
        return Task::query()->with(['category:id,icon', 'levelInfo:level,name'])
            ->withCount(['userTask' => function ($query) {
                $query->whereIn('status', [0, 1, 2]);
            }])
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->paginate(10);
    }

    /**
     * 获取任务详情
     *
     * @param int $id
     * @return mixed
     */
    public function getDetail(int $id)
    {
        return Task::query()->with(['category:id,banner,job_step,audit_sample', 'levelInfo:level,name'])->withCount(['userTask' => function ($query) {
            $query->whereIn('status', [0, 1, 2]);
        }])->withCount(['userTask as user_complete_count' => function ($query) {
            $query->where('status', 2);
        }])->where('id', $id)->first();
    }

    /**
     * 通过ID获取任务
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?Task
    {
        return Task::query()->where('id', $id)->first();
    }

    /**
     * 添加任务
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Task::query()->create($data);
    }
}