<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Service\Dao\UserCreditRecordDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserSignInRecordDAO;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\DbConnection\Db;

/**
 * 信用服务
 *
 *
 * @package App\Service
 */
class CreditService extends Base
{
    /**
     * 签到
     * @Cacheable(prefix="sign_in", value="#{user_id}", ttl=3)
     *
     * @param int $user_id
     * @return bool
     */
    public function signIn(int $user_id)
    {
        // 判断用户今日是否签到过
        if ($this->container->get(UserSignInRecordDAO::class)->checkUserTodaySign($user_id)) {
            $this->error('logic.SIGNED_IN_TODAY');
        }

        Db::beginTransaction();
        // 执行签到操作
        try {
            $credit = 2;

            // 签到记录
            $this->container->get(UserSignInRecordDAO::class)->create([
                'user_id' => $user_id,
                'credit' => $credit
            ]);

            // 信用分记录
            $this->container->get(UserCreditRecordDAO::class)->create([
                'user_id' => $user_id,
                'type' => 4,
                'credit' => $credit
            ]);

            // 增加用户信用分
            $user = $this->container->get(UserDAO::class)->getUserById($user_id);

            $user->increment('credit', $credit);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('signIn')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }

        return true;
    }
}