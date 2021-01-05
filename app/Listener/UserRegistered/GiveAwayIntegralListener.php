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
use App\Service\Dao\UserDAO;

use Hyperf\DbConnection\Db;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * 注册后赠送上级积分
 *
 * @Listener()
 *
 * @package App\Listener
 */
class GiveAwayIntegralListener extends AbstractListener implements ListenerInterface
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
        $user        = $event->user;
        $integralNum = getConfig('SubRegisteredGiveAwayParentIntegralNum', 0);
        if ($integralNum > 0 && $user->parent_id > 0) {
            go(function () use ($integralNum, $user) {
                $this->container->get(UserDAO::class)->update($user->parent_id, [
                    'integral' => Db::raw('integral+' . $integralNum)
                ]);
            });
        }
    }
}