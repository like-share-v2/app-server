<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Listener\UserRegistered;

use App\Event\UserRegisteredEvent;
use App\Listener\AbstractListener;
use App\Model\User;

use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserMemberDAO;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * 新用户赠送VIP
 *
 * @Listener()
 *
 * @package App\Listener
 */
class GiveAwayVIP extends AbstractListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            UserRegisteredEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     *
     * @param object $event
     */
    public function process(object $event)
    {
        if (!($event instanceof UserRegisteredEvent)) {
            return;
        }
        if (!($event->user instanceof User)) {
            return;
        }
        $user          = $event->user;
        $default_level = getConfig('default_member', -1);
        if ($level = $this->container->get(UserLevelDAO::class)->findByLevel($default_level)) {
            $duration = getConfig('default_member_duration', $level->duration);
            // 添加用户会员关系
            $this->container->get(UserMemberDAO::class)->create([
                'user_id'        => $user->id,
                'level'          => $level->level,
                'effective_time' => $duration <= 0 ? -1 : strtotime(date('Y-m-d',time() + $duration))
            ]);
        }
    }
}