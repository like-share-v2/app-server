<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserLevel;

/**
 * 用户等级DAO
 *
 *
 * @package App\Service\Dao
 */
class UserLevelDAO extends Base
{
    /**
     * 会员充值页列表
     *
     * @return mixed
     */
    public function getList()
    {
        return UserLevel::query()->orderBy('level', 'asc')->get();
    }

    /**
     * 获取所有会员等级
     *
     * @param array $params
     * @return mixed
     */
    public function getAllList(array $params = [])
    {
        $model = UserLevel::query();

        if (isset($params['rebate_type']) && in_array($params['rebate_type'], [1, 2])) {
            switch ($params['rebate_type']) {
                case 1:
                    $model->with('rechargeLevelRebate');
                    break;
                case 2:
                    $model->with('taskLevelRebate');
                    break;
            }
        }

        return $model->orderBy('level', 'asc')->get();
    }

    /**
     * 获取会员等级
     *
     * @param int $level
     * @return mixed
     */
    public function findByLevel(int $level): ?UserLevel
    {
        return UserLevel::query()->where('level', $level)->first();
    }
}