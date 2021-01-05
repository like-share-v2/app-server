<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserNotify;
use Hamcrest\Core\IsNotTest;

/**
 * 用户通知DAO
 *
 *
 * @package App\Service\Dao
 */
class UserNotifyDAO extends Base
{
    /**
     * 用户通知/新闻列表
     *
     * @param int $user_id
     * @param int $type
     * @return mixed
     */
    public function get(int $user_id, int $type)
    {
        $model = UserNotify::query();

        switch ($type) {
            case 1: // 消息
                $model->where('type', 1);
                break;
            case 2: // 新闻
                $model->where('type', 2);
                break;
            default:
                $this->error('logic.CHECK_USER_NOTIFY_ERROR');
        }

        return $model->select(['id', 'title', 'type', 'content', 'created_at'])->whereIn('user_id', [$user_id, 0])->orderByDesc('sort')->orderByDesc('id')->paginate(10);
    }

    /**
     * 通过ID查找
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return UserNotify::query()->where('id', $id)->select(['id', 'user_id', 'type', 'title', 'content', 'created_at'])->first();
    }

    /**
     * 通过用户ID获取通知IDS
     *
     * @param int $user_id
     * @param int $type
     * @param array $read_ids
     * @return array
     */
    public function getIdsByUserId(int $user_id, int $type, array $read_ids)
    {
        return array_column(UserNotify::query()->whereIn('user_id', [$user_id, 0])->whereNotIn('id', $read_ids)->where('type', $type)->select(['id'])->get()->toArray(), 'id');
    }

    public function create(array $data)
    {
        return UserNotify::query()->create($data);
    }
}