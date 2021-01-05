<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service\Dao;

use App\Common\Base;
use App\Model\UserRecharge;
use Hyperf\DbConnection\Db;

/**
 * 用户充值DAO
 *
 *
 * @package App\Service\Dao
 */
class UserRechargeDAO extends Base
{
    /**
     * 获取指定用户列表的充值总额
     *
     * @param array $user_ids
     * @return int
     */
    public function getAmountSumByUserIds(array $user_ids)
    {
        return UserRecharge::query()->whereIn('user_id', $user_ids)->where('status', 1)->sum('balance');
    }

    /**
     * 通过用户ID获取充值列表
     *
     * @param int $user_id
     * @return mixed
     */
    public function getListByUserId(int $user_id)
    {
        return UserRecharge::query()
            ->with(['payment:id,pay_no,status', 'userLevel:level,name'])
            ->where('user_id', $user_id)
            ->orderByDesc('recharge_time')
            ->orderByDesc('id')
            ->paginate(10);
    }

    /**
     * 添加用户充值记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserRecharge::query()->create($data);
    }

    public function getLastTenRecord()
    {
        return UserRecharge::query()->with(['user:id,nickname', 'userLevel:level,name'])->orderByDesc('id')->limit(10)->get();
    }

    /**
     * 获取重复充值会员人数
     *
     * @param array $params
     * @param array $lower_ids
     * @return array
     */
    public function getOverLayMemberIds(array $params, array $lower_ids)
    {
        $model = UserRecharge::query()->whereIn('user_id', $lower_ids);

        if (isset($params['time']) && is_array($params['time']) && count($params['time']) === 2) {
            $model->whereBetween('recharge_time', $params['time']);
        }

        return array_column($model->where('status', 1)->groupBy('user_id')->get()->toArray(), 'user_id');
    }

    /**
     * 获取充值会员下级数量
     *
     * @param array $params
     * @param array $lower_ids
     * @return int
     */
    public function getRechargeLevelUserCount(array $params, array $lower_ids)
    {
        $model = UserRecharge::query()->whereIn('user_id', $lower_ids);

        if (isset($params['time']) && is_array($params['time']) && count($params['time']) === 2) {
            $model->whereBetween('recharge_time', $params['time']);
        }

        return $model->where('status', 1)->count(Db::raw('DISTINCT(user_id)'));
    }

    /**
     * 查看用户是否充值过该等级
     *
     * @param int $user_id
     * @param int $level
     * @return bool
     */
    public function checkUserRechargeLevel(int $user_id, int $level)
    {
        return UserRecharge::query()
            ->where('user_id', $user_id)
            ->where('level', $level)
            ->where('status', 1)
            ->exists();
    }

    /**
     * 查看用户是否十秒内充值过该等级
     *
     * @param int $user_id
     * @param int $level
     * @return bool
     */
    public function checkUserLastTenSecondRecharge(int $user_id, int $level)
    {
        return UserRecharge::query()
            ->where('user_id', $user_id)
            ->where('level', $level)
            ->where('status', 1)
            ->where('recharge_time', '>', time() - 10)
            ->exists();
    }
}