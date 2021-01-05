<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserBill;
use Hyperf\DbConnection\Db;

/**
 * 用户账单DAO
 *
 *
 * @package App\Service\Dao
 */
class UserBillDAO extends Base
{
    /**
     * 创建用户账单
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserBill::query()->create($data);
    }

    /**
     * 获取用户账单列表
     *
     * @param int $user_id
     * @param int $way
     * @return mixed
     */
    public function getListByUserId(int $user_id, int $way)
    {
        $model = UserBill::query()->where('user_id', $user_id);

        switch ($way) {
            case 2:
                $model->where('type', 1);
                break;
            case 3:
                $model->whereIn('type', [2, 3]);
                break;
            default :
                break;
        }

        return $model->orderByDesc('created_at')->orderByDesc('id')->paginate(10);
    }

    /**
     * 通过用户ID获取今日收益
     *
     * @param int $user_id
     * @return int
     */
    public function getTodayProfitByUserId(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->where('balance', '>', 0)
            ->whereIn('type', [1, 2, 3, 11])
            ->where('created_at', '>', strtotime(date('Y-m-d')))
            ->sum('balance');
    }

    /**
     * 获取用户团队收益
     *
     * @param int $user_id
     * @return int
     */
    public function getTeamProfitByUserId(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->whereIn('type', [2, 3])
            ->sum('balance');
    }

    /**
     * 获取用户任务收益
     *
     * @param int $user_id
     * @return int
     */
    public function getTaskProfitByUserId(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->where('type', 1)
            ->sum('balance');
    }

    /**
     * 获取完成任务数据
     *
     * @return mixed
     */
    public function getCompleteTaskData()
    {
        return UserBill::query()->with(['user:id,avatar,nickname'])
            ->where('type', 1)
            ->where('created_at', '>', strtotime(date('Y-m-d')))
            ->select(['user_id', Db::raw('SUM(balance) as amount'), Db::raw('COUNT(*) as count')])
            ->groupBy('user_id')
            ->get();
    }

    public function getPublishMemberData()
    {
        return UserBill::query()->with(['user:id,avatar,nickname'])
            ->where('type', 9)
            ->where('created_at', '>', strtotime(date('Y-m-d')))
            ->select(['user_id', Db::raw('SUM(balance) as amount'), Db::raw('COUNT(*) as count')])
            ->groupBy('user_id')
            ->get();
    }

    public function getTeamProfitByLowerIds(int $user_id, array $lower_ids)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->whereIn('type', [2, 3])
            ->whereIn('low_id', $lower_ids)
            ->sum('balance');
    }

    public function getSumByUserIdAndLowId(int $user_id, int $low_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->where('low_id', $low_id)
            ->sum('balance');
    }

    public function getCommissionRecords(int $user_id)
    {
        return UserBill::query()->with(['low:id,account'])
            ->select(['balance', 'low_id', 'created_at'])
            ->where('user_id', $user_id)
            ->where('low_id', '<>', 0)
            ->paginate(10);
    }

    public function getLowersPaymentSumAmount(array $lower_ids)
    {
        return UserBill::query()->whereIn('user_id', $lower_ids)
            ->whereNotIn('type', [4])
            ->where('balance', '<', 0)
            ->sum('balance');
    }

    public function getIncomeSumAmountByUserIds(array $lower_ids)
    {
        return UserBill::query()->whereIn('user_id', $lower_ids)
            ->whereNotIn('type', [5, 12])
            ->where('balance', '>', 0)
            ->sum('balance');
    }

    public function getUserRechargeAmountSum(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->whereIn('type', [6, 8])
            ->sum('balance');
    }

    public function getUserPaymentAmountSum(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)
            ->where('balance', '<', 0)
            ->sum('balance');
    }

    public function getAmountSum(array $params, array $types, array $lower_ids)
    {
        $model = UserBill::query()->whereIn('user_id', $lower_ids);

        if (count($types) > 0) {
            $model->whereIn('type', $types);
        }

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->sum('balance');
    }

    public function getFirstRechargeUserCount(array $params, array $user_ids)
    {
        $model = UserBill::query()->whereIn('type', [6, 8])->whereIn('user_id', $user_ids);

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return count($model->select(['user_id'])->groupBy('user_id')->get());
    }

    public function checkUserLastTenWithdrawal(int $user_id)
    {
        return UserBill::query()->where('user_id', $user_id)->where('type', 4)
            ->where('created_at', '>', time() - 10)
            ->exists();
    }

    /**
     * 获取下级账单列表
     *
     * @param array $lower_ids
     * @param array $params
     * @return mixed
     */
    public function getLowerList(array $lower_ids, array $params)
    {
        $model = UserBill::query()->whereIn('user_id', $lower_ids);

        if (isset($params['user_id']) && $params['user_id'] !== '') {
            $model->where('user_id', $params['user_id']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }
}