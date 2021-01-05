<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Request\Task\SubmitRequest;
use App\Request\Task\TaskRequest;
use App\Service\Dao\TaskCategoryDAO;
use App\Service\Dao\TaskDAO;
use App\Middleware\AuthMiddleware;
use App\Service\Dao\UserTaskDAO;
use App\Service\TaskService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 任务控制器
 *
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class TaskController extends AbstractController
{
    /**
     * 获取任务列表
     *
     * @GetMapping(path="")
     */
    public function getList()
    {
        $params = $this->request->all();

        $user_id = JwtInstance::instance()->build()->getId();

        $received_ids = $this->container->get(UserTaskDAO::class)->getUserReceivedIds($user_id);

        $result = $this->container->get(TaskDAO::class)->getList($params, $received_ids)->toArray();

        // 计算剩余数量
        $data = array_map(function($task) {
            $task['remaining_quantity'] = $task['num'] - $task['user_task_count'];
            return $task;
        }, $result['data']);

        $data = array_values(array_filter($data, function ($task) {
            return $task['remaining_quantity'] > 0;
        }));

        $result['data'] = $data;

        $this->success($result);
    }

    /**
     * 获取任务详情
     *
     * @GetMapping(path="detail/{id}")
     * @param int $id
     */
    public function detail(int $id)
    {
        $result = $this->container->get(TaskDAO::class)->getDetail($id);

        $this->success($result);
    }

    /**
     * 用户领取任务
     *
     * @PostMapping(path="receive")
     */
    public function receive()
    {
        $id = (int)$this->request->input('id', 0);

        $result = $this->container->get(TaskService::class)->receive($id);

        $this->success($result);
    }

    /**
     * 用户取消任务
     *
     * @PutMapping(path="cancel")
     */
    public function cancel()
    {
        $user_task_id = (int)$this->request->input('id', 0);

        $this->container->get(TaskService::class)->cancel($user_task_id);

        $this->success();
    }

    /**
     * 用户提交任务
     *
     * @PutMapping(path="submit")
     * @param SubmitRequest $request
     */
    public function submit(SubmitRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskService::class)->submit((int)$params['id'], $params['image']);

        $this->success();
    }

    /**
     * 用户任务列表
     *
     * @GetMapping(path="userTaskList")
     */
    public function userTaskList()
    {
        $type = (int)$this->request->input('type', 0);

        $result = $this->container->get(TaskService::class)->getUserTaskList($type);

        $this->success($result);
    }

    /**
     * 任务分类列表
     *
     * @GetMapping(path="category")
     */
    public function category()
    {
        $result = $this->container->get(TaskCategoryDAO::class)->get();

        $this->success($result);
    }

    /**
     * 发布任务接口
     *
     * @PostMapping(path="publish")
     * @param TaskRequest $request
     */
    public function create(TaskRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskService::class)->create($params);

        $this->success();
    }

    /**
     * 获取发布任务列表
     *
     * @GetMapping(path="publish")
     */
    public function getPublish()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(TaskDAO::class)->getPublishList($user_id)->toArray();

        // 计算剩余数量
        $data = array_map(function($task) {
            $task['remaining_quantity'] = $task['num'] - $task['user_task_count'];
            return $task;
        }, $result['data']);

        $data = array_values(array_filter($data, function ($task) {
            return $task['remaining_quantity'] > 0;
        }));

        $result['data'] = $data;

        $this->success($result);
    }
}