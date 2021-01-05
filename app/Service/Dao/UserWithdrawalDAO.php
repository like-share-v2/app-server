<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserWithdrawal;

use Hyperf\DbConnection\Db;

/**
 * 用户提现DAO
 *
 *
 * @package App\Service\Dao
 */
class UserWithdrawalDAO extends Base
{
    /**
     * 添加用户提现记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserWithdrawal::query()->create($data);
    }

    /**
     * 获取用户今日申请提现次数
     *
     * @param int $user_id
     * @return int
     */
    public function getTodayUserWithdrawalCount(int $user_id)
    {
        return UserWithdrawal::query()->where('user_id', $user_id)->where('created_at', '>', strtotime(date('Y-m-d')))->count();
    }

    /**
     * 获取用户提现列表
     *
     * @param int $user_id
     * @return mixed
     */
    public function get(int $user_id)
    {
        return UserWithdrawal::query()->where('user_id', $user_id)->orderByDesc('created_at')->orderByDesc('id')->paginate(10);
    }

    /**
     * 通过用户IDS获取提现成功总金额
     *
     * @param array $user_ids
     * @return int
     */
    public function getAmountSumByUserIds(array $user_ids)
    {
        return UserWithdrawal::query()->whereIn('user_id', $user_ids)->where('status', 1)->sum('amount');
    }

    /**
     * 获取用户本月提现次数
     *
     * @param int $user_id
     * @return int
     */
    public function getMonthWithdrawalCount(int $user_id)
    {
        return UserWithdrawal::query()->where('user_id', $user_id)->where('created_at', '>', strtotime(date('Y-m')))->count();
    }

    public function getAmountSum(array $params, array $user_ids)
    {
        $model = UserWithdrawal::query()->where('status', 1)->whereIn('user_id', $user_ids);

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return $model->sum('amount');
    }

    public function getUserCount(array $params, array $user_ids): int
    {
        $model = UserWithdrawal::query()->where('status', 1)->whereIn('user_id', $user_ids);

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return $model->count(Db::raw('DISTINCT(user_id)'));
    }

    /**
     * 获取用户提现次数
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getUserWithdrawalCount(int $user_id)
    {
        return UserWithdrawal::query()->where('user_id', $user_id)->whereIn('status', [0, 1])->count();
    }
}