<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Model\UserLevelBuyNum;

/**
 * UserLevelBuyNumDAO
 *
 * @author
 * @package App\Service\Dao
 */
class UserLevelBuyNumDAO
{
    /**
     * @param int $user_id
     * @param int $level
     *
     * @return mixed
     */
    public function update(int $user_id, int $level): ?UserLevelBuyNum
    {
        /** @var UserLevelBuyNum $find */
        if ($find = UserLevelBuyNum::where('user_id', $user_id)->where('level', $level)->first()) {
            $find->num += 1;
            $find->save();
            return $find;
        }

        return UserLevelBuyNum::query()->create([
            'user_id' => $user_id,
            'level'   => $level,
            'num'     => 1
        ]);
    }

    /**
     * @param int $user_id
     * @param int $level
     *
     * @return int
     */
    public function get(int $user_id, int $level)
    {
        return (int)UserLevelBuyNum::where('user_id', $user_id)->where('level', $level)->value('num');
    }
}