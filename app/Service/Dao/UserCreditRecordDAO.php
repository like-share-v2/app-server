<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserCreditRecord;

/**
 * 用户信用分记录DAO
 *
 *
 * @package App\Service\Dao
 */
class UserCreditRecordDAO extends Base
{
    /**
     * 用户信用分变动记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserCreditRecord::query()->create($data);
    }

    /**
     * 查看个人信用
     *
     * @param int $user_id
     * @param int $type
     * @return mixed
     */
    public function get(int $user_id, int $type)
    {
        $model = UserCreditRecord::query()->where('user_id', $user_id);

        switch ($type) {
            case 1: // 已加信用
                $model->where('credit', '>', 0);
                break;
            case 2: // 已扣信用
                $model->where('credit', '<', 0);
                break;
            default:
                $this->error('logic.CHECK_USER_CREDIT_ERROR');
        }

        return $model->orderByDesc('created_at')->orderByDesc('id')->paginate(10);
    }
}