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
 * UserRegisteredEvent
 *
 * @author
 * @package App\Event
 */
class UserRegisteredEvent
{
    /**
     * @var User
     */
    public $user;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}