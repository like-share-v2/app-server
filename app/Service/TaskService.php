<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\Dao\TaskAuditDAO;
use App\Service\Dao\TaskCategoryDAO;
use App\Service\Dao\TaskDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserMemberDAO;
use App\Service\Dao\UserTaskDAO;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\DbConnection\Db;

/**
 * 任务服务
 *
 *
 * @package App\Service
 */
class TaskService extends Base
{
    /**
     * 领取任务
     *
     * @param int $task_id
     */
    public function receive(int $task_id)
    {
        $user = JwtInstance::instance()->build()->getUser();

        // 获取任务
        $task = $this->container->get(TaskDAO::class)->findById($task_id);

        // 判断任务是否存在
        if (!$task) {
            $this->error('logic.TASK_NOT_FOUND');
        }

        // 判断会员等级是否过期
        /* if ($user->getAttributes()['effective_time'] < time()) {
            $this->error('logic.MEMBER_EXPIRED');
        } */

        // 判断会员等级
        /* if ($user->level !== $task->level) {
            $this->error('logic.USER_LEVEL_NOT_REACH_TASK_LEVEL');
        } */

        // 判断会员等级
        $user_member = $this->container->get(UserMemberDAO::class)->firstByUserIdLevel($user->id, $task->level);
        if (!$user_member) {
            $this->error('logic.USER_LEVEL_NOT_REACH_TASK_LEVEL');
        }

        // 判断会员等级是否过期
        if ($user_member->getAttributes()['effective_time'] > -1 && $user_member->getAttributes()['effective_time'] < time()) {
            $this->error('logic.MEMBER_EXPIRED');
        }

        // 判断用户是否领取 (仅限 进行中，审批中，已通过 三状态)
        if ($this->container->get(UserTaskDAO::class)->checkUserTaskExisted($user->id, $task_id)) {
            $this->error('logic.USER_RECEIVED_TASK');
        }

        // 判断用户信用分
        if ($user->credit < 200) {
            $this->error('logic.TASK_INSUFFICIENT_CREDIT_SCORE');
        }

        // 判断会员等级今日领取限制
        $user_today_task_count = $this->container->get(UserTaskDAO::class)->getUserTodayTaskCountByLevel($user->id,$task->level);
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel($task->level);
        if ($user_today_task_count >= $user_level->task_num) {
            $this->error('logic.TODAY_TASKS_REACHED_LIMIT');
        }

        // 判断状态
        if ($task->status !== 1) {
            $this->error('logic.TASK_STATUS_ERROR');
        }

        $task_num = $this->container->get(UserTaskDAO::class)->getTaskCount($task_id);

        // 判断剩余数量
        if ($task->num <= $task_num) {
            $this->error('logic.TASK_SHORTAGE_IN_NUMBER');
        }

        // 加锁
        $key = sprintf('TaskLock:%d', $user->id);
        if (!$this->redis->setnx(sprintf('TaskLock:%d', $user->id), true)){
            $this->error('logic.SERVER_ERROR');
        }
        if (!$setExpire = $this->redis->expire($key, 5)) {
            $this->error('logic.SERVER_ERROR');
        }
        Db::beginTransaction();
        try {
            if ($this->container->get(UserTaskDAO::class)->checkUserTaskExisted($user->id, $task_id)) {
                $this->error('logic.USER_RECEIVED_TASK');
            }

            // 创建用户领取任务记录
            $result = $this->container->get(UserTaskDAO::class)->create([
                'user_id' => $user->id,
                'task_id' => $task_id,
                'status' => 0,
                'amount' => $task->amount
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('task')->error($e->getMessage());
            $this->redis->del($key);
            $this->error('logic.SERVER_ERROR');
        }

        $this->redis->del($key);

        // 领取任务清除缓存
        $this->flushCache('user_received_ids_update', [$user->id]);

        return $result;
    }

    /**
     * 取消任务
     *
     * @param int $user_task_id
     */
    public function cancel(int $user_task_id)
    {
        $user = JwtInstance::instance()->build()->getUser();

        $user_task = $this->container->get(UserTaskDAO::class)->findById($user_task_id);

        // 未找到用户任务
        if (!$user_task || $user->id !== $user_task->user_id) {
            $this->error('logic.USER_TASK_NOT_FOUND');
        }

        // 判断任务状态
        if ($user_task->status !== 0) {
            $this->error('logic.USER_TASK_STATUS_ERROR');
        }

        // 获取任务
        $task = $this->container->get(TaskDAO::class)->findById($user_task->task_id);

        // 判断任务是否存在
        if (!$task) {
            $this->error('logic.TASK_NOT_FOUND');
        }

        // 修改任务状态
        $user_task->status = 4;
        $user_task->cancel_time = time();
        $user_task->save();

        // 清除缓存
        $this->flushCache('user_received_ids_update', [$user->id]);
    }

    /**
     * 提交任务
     *
     * @param int $user_task_id
     * @param string $image
     */
    public function submit(int $user_task_id, string $image)
    {
        $user = JwtInstance::instance()->build()->getUser();

        $user_task = $this->container->get(UserTaskDAO::class)->findById($user_task_id);

        // 未找到用户任务
        if (!$user_task || $user->id !== $user_task->user_id) {
            $this->error('logic.USER_TASK_NOT_FOUND');
        }

        // 判断用户任务状态
        if ($user_task->status !== 0) {
            $this->error('logic.USER_TASK_STATUS_ERROR');
        }

        $user_task->status = 1;
        $user_task->image = $image;
        $user_task->submit_time = time();
        $user_task->save();
    }

    /**
     * 获取用户任务列表
     *
     * @param int $type
     * @return mixed
     */
    public function getUserTaskList(int $type)
    {
        switch ($type) {
            case 0 : // 进行中
                $status = [0];
                break;
            case 1 : // 审核中
                $status = [1];
                break;
            case 2 : // 已审核
                $status = [2, 3];
                break;
            default :
                $status = [];
                $this->error('logic.CHECK_USER_TASK_STATUS_ERROR');
                break;
        }

        $user_id = JwtInstance::instance()->build()->getId();

        return $this->container->get(UserTaskDAO::class)->getUserTaskList($user_id, $status);
    }

    /**
     * 用户添加任务
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 检查任务分类
        $category = $this->container->get(TaskCategoryDAO::class)->firstById((int)$params['category_id']);
        if (!$category) {
            $this->error('logic.TASK_CATEGORY_NOT_FOUND');
        }

        // 检查会员等级
        $level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if (!$level) {
            $this->error('logic.USER_LEVEL_NOT_FOUND');
        }

        // 检查价格
        if ((float)$params['amount'] < $category->lowest_price) {
            $this->error(__('logic.AMOUNT_GT_TASK_LOWEST_PRICE', ['amount' => (string)$category->lowest_price]));
        }

        // 检查用户余额
        $user = JwtInstance::instance()->build()->getUser();
        $create_amount = (float)$params['amount'] * (int)$params['num'];

        // 用户余额不足
        if ($user->balance < $create_amount) {
            $this->error('logic.USER_BALANCE_NOT_ENOUGH');
        }

        Db::beginTransaction();
        try {
            // 创建用户账单
            $this->container->get(UserBillDAO::class)->create([
                'user_id' => $user->id,
                'type' => 9,
                'balance' => - $create_amount,
                'before_balance' => $user->balance,
                'after_balance' => $user->balance - $create_amount
            ]);

            // 扣除用户余额
            $user->decrement('balance', $create_amount);

            // 创建审核任务
            $this->container->get(TaskAuditDAO::class)->create([
                'user_id' => $user->id,
                'category_id' => (int)$params['category_id'],
                'level' => (int)$params['level'],
                'title' => trim($params['title']),
                'description' => trim($params['description']),
                'url' => trim($params['url']),
                'amount' => (float)$params['amount'],
                'num' => (int)$params['num']
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('task')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}