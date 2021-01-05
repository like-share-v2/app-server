<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service;

use App\Common\Base;
use App\Service\Dao\UserNotifyDAO;
use App\Service\Dao\UserReadDAO;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\DbConnection\Db;

/**
 * 用户通知服务
 *
 *
 * @package App\Service
 */
class UserNotifyService extends Base
{
    /**
     * 查看通知详情
     *
     * @param int $notify_id
     * @param int $user_id
     * @return mixed
     */
    public function read(int $notify_id, int $user_id)
    {
        $notify = $this->container->get(UserNotifyDAO::class)->findById($notify_id);

        // 判断通知
        if (!$notify) {
            $this->error('logic.NOTIFY_NOT_FOUND');
        }

        // 判断用户
        if ($notify->user_id !== 0 && $notify->user_id !== $user_id) {
            $this->error('logic.NOTIFY_NOT_FOUND');
        }

        // 设为已读
        $this->container->get(UserReadDAO::class)->firstOrCreate($user_id, $notify_id);

        // 清除用户已读记录缓存
        $this->eventDispatcher->dispatch(new DeleteListenerEvent('user-read-update', [$user_id]));

        return $notify;
    }

    /**
     * 设置已读
     *
     * @param int $user_id
     * @param int $type
     */
    public function setAllRead(int $user_id, int $type)
    {
        // 获取用户已读的ID
        $read_ids = array_column($this->container->get(UserReadDAO::class)->get($user_id), 'notify_id');

        // 获取该用户未读的消息/新闻IDS
        $notify_ids = $this->container->get(UserNotifyDAO::class)->getIdsByUserId($user_id, $type, $read_ids);

        $insert_data = array_map(function ($notify_id) use ($user_id) {
            return ['notify_id' => $notify_id, 'user_id' => $user_id];
        }, $notify_ids);

        Db::table('user_read_record')->insert($insert_data);

        // 清除用户已读记录缓存
        $this->eventDispatcher->dispatch(new DeleteListenerEvent('user-read-update', [$user_id]));
    }

    /**
     * 设为未读
     *
     * @param int $user_id
     * @param int $notify_id
     */
    public function setUnread(int $user_id, int $notify_id)
    {
        $notify = $this->container->get(UserNotifyDAO::class)->findById($notify_id);

        // 判断通知
        if (!$notify) {
            $this->error('logic.NOTIFY_NOT_FOUND');
        }

        // 判断用户
        if ($notify->user_id !== 0 && $notify->user_id !== $user_id) {
            $this->error('logic.NOTIFY_NOT_FOUND');
        }

        // 设为未读
        $this->container->get(UserReadDAO::class)->delete($user_id, $notify_id);

        // 清除用户已读记录缓存
        $this->eventDispatcher->dispatch(new DeleteListenerEvent('user-read-update', [$user_id]));

        $this->success();
    }
}