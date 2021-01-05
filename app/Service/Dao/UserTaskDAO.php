<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserTask;
use Hyperf\Cache\Annotation\Cacheable;

/**
 * 用户任务DAO
 *
 *
 * @package App\Service\Dao
 */
class UserTaskDAO extends Base
{
    /**
     * 检查用户是否领取该任务
     *
     * @param int $user_id
     * @param int $task_id
     * @return bool
     */
    public function checkUserTaskExisted(int $user_id, int $task_id)
    {
        return UserTask::query()->where('user_id', $user_id)->where('task_id', $task_id)->whereIn('status', [0, 1, 2])->exists();
    }

    /**
     * 获取用户今日任务数量
     *
     * @param int $user_id
     * @return int
     */
    public function getUserTodayTaskCount(int $user_id)
    {
        return UserTask::query()->where('user_id', $user_id)->whereIn('status', [0, 1, 2, 3])->where('created_at', '>', strtotime(date('Y-m-d')))->count();
    }

    /**
     * 获取用户今日任务数量
     *
     * @param int $user_id
     * @param int $level
     * @return int
     */
    public function getUserTodayTaskCountByLevel(int $user_id, int $level)
    {
        return UserTask::query()->where('user_id', $user_id)->whereHas('task', function ($query) use ($level) {
            $query->where('level', $level);
        })->whereIn('status', [0, 1, 2, 3])->where('created_at', '>', strtotime(date('Y-m-d')))->count();
    }

    /**
     * 获取用户任务
     *
     * @param int $user_id
     * @param $task_id
     * @return mixed
     */
    public function findByUserIdAndTaskId(int $user_id, $task_id): ?UserTask
    {
        return UserTask::query()->where('user_id', $user_id)->where('task_id', $task_id)->first();
    }

    /**
     * 创建用户领取任务记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserTask::query()->create($data);
    }

    /**
     * 获取用户任务列表
     *
     * @param int $user_id
     * @param array $status
     * @return mixed
     */
    public function getUserTaskList(int $user_id, array $status)
    {
        return UserTask::query()->with('task:id,title,url')->where('user_id', $user_id)->whereIn('status', $status)->orderByDesc('updated_at')->orderByDesc('id')->paginate(10);
    }

    /**
     * 通过ID查找用户任务
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserTask
    {
        return UserTask::query()->where('id', $id)->first();
    }

    /**
     * 获取用户领取任务ID
     *
     * @Cacheable(prefix="user_received_ids", ttl=9000, listener="user_received_ids_update")
     * @param int $user_id
     * @return array
     */
    public function getUserReceivedIds(int $user_id)
    {
        return array_column(UserTask::query()->where('user_id', $user_id)
            ->whereIn('status', [0, 1, 2])
            ->select(['task_id'])
            ->get()
            ->toArray(), 'task_id');
    }

    /**
     * 获取任务已被领取数量
     *
     * @param int $task_id
     * @return int
     */
    public function getTaskCount(int $task_id)
    {
        return UserTask::query()->where('task_id', $task_id)->whereIn('status', [0, 1, 2])->count();
    }

    /**
     * 获取用户完成任务数量
     *
     * @param int $user_id
     * @return int
     */
    public function getUserCompleteTaskCount(int $user_id)
    {
        return UserTask::query()->where('user_id', $user_id)->where('status', 2)->count();
    }

    public function getCompleteUserCount(array $params, array $lower_ids)
    {
        $model = UserTask::query()->whereIn('user_id', $lower_ids);

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return count($model->where('status', 2)->groupBy('user_id')->select(['id'])->get());
    }
}