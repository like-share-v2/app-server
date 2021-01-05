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
use App\Service\Dao\UserNotifyDAO;
use App\Middleware\AuthMiddleware;
use App\Service\Dao\UserReadDAO;
use App\Service\UserNotifyService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户消息控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class UserNotifyController extends AbstractController
{
    /**
     * 获取用户消息列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $type = (int)$this->request->input('type', 1);

        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(UserNotifyDAO::class)->get($user_id, $type);

        // 判断消息是否已读
        $user_read_record_list = $this->container->get(UserReadDAO::class)->get($user_id);
        $notify_ids = array_column($user_read_record_list, 'notify_id');
        $data = array_map(function ($val) use ($notify_ids) {
            if (in_array($val['id'], $notify_ids)) {
                $val['is_read'] = 1;
            } else {
                $val['is_read'] = 0;
            }
            return $val;
        }, $result->toArray()['data']);

        $result = $result->toArray();
        $result['data'] = $data;

        $this->success($result);
    }

    /**
     * 查看消息详情
     *
     * @GetMapping(path="read")
     */
    public function read()
    {
        $id = (int)$this->request->input('id', 0);

        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(UserNotifyService::class)->read($id, $user_id);

        $this->success($result);
    }

    /**
     * 全部已读
     *
     * @PostMapping(path="set_all_read")
     */
    public function setAllRead()
    {
        $type = (int)$this->request->input('type', 1);

        $user_id = JwtInstance::instance()->build()->getId();

        $this->container->get(UserNotifyService::class)->setAllRead($user_id, $type);

        $this->success();
    }

    /**
     * 设置未读
     *
     * @DeleteMapping(path="set_unread")
     */
    public function setUnread()
    {
        $id = (int)$this->request->input('id', 0);

        $user_id = JwtInstance::instance()->build()->getId();

        $this->container->get(UserNotifyService::class)->setUnread($user_id, $id);

        $this->success();
    }
}