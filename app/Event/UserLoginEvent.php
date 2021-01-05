<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Event;

use App\Model\User;

/**
 * 用户登录事件
 *
 *
 * @package App\Event
 */
class UserLoginEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * UserLoginEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}