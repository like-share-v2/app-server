<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Listener;

use App\Event\UserLoginEvent;
use App\Model\User;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * 用户登录监听器
 *
 * @Listener()
 *
 * @package App\Listener
 */
class UserLoginListener extends AbstractListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            UserLoginEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     * @param object $event
     */
    public function process(object $event)
    {
        if (!($event->user instanceof User)) {
            return;
        }

        // 记录登陆行为
        $event->user->last_login_time = time();
        $event->user->save();
    }
}