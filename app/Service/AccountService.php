<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Service;

use App\Common\Base;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserInfoDAO;

use Hyperf\Di\Annotation\Inject;

/**
 * 账号服务
 *
 *
 * @package App\Service
 */
class AccountService extends Base
{
    /**
     * 用户DAO
     *
     * @Inject()
     * @var UserDAO
     */
    private $userDAO;

    /**
     * 修改用户昵称
     *
     * @param int $user_id
     * @param string $nickname
     */
    public function changeNickname(int $user_id, string $nickname)
    {
        $user = $this->userDAO->getUserById($user_id);
        // 防止修改为同一昵称
        if ($user->nickname === $nickname) return;
        if ($this->userDAO->checkValueIsUsed('nickname', $nickname)) {
            $this->error('logic.NICKNAME_USED');
        }
        $user->nickname = $nickname;
        $user->save();
    }

    /**
     * 修改头像
     *
     * @param int $user_id
     * @param string $avatar
     */
    public function changeAvatar(int $user_id, string $avatar)
    {
        $user = $this->userDAO->getUserById($user_id);
        // 防止修改为同一头像
        if ($user->avatar === $avatar) return;
        $user->avatar = $avatar;
        $user->save();
    }

    /**
     * 修改手机号码
     *
     * @param int $user_id
     * @param string $phone
     * @param string $code
     */
    public function changePhone(int $user_id, string $phone, string $code, string $add_num)
    {
        $user = $this->userDAO->getUserById($user_id);
        // 防止修改为同一手机号码
        if ($user->phone === $phone) return;

        // 检查验证码
        if (!$this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->checkVerifyCode($add_num . $phone, 'change_phone', $code)) {
            $this->error('logic.CODE_ERROR');
        }

        // 清除验证码
        $this->container->get(\Zunea\HyperfKernel\Service\SMSService::class)->destroyVerifyCode($add_num . $phone, 'change_phone');

        // 检测手机是否被使用
        if ($user->phone !== $phone && $this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
            $this->error('logic.PHONE_EXIST');
        }
        $user->phone = $phone;
        $user->country_code = $add_num;
        $user->save();
    }

    /**
     * 修改密码
     *
     * @param int    $user_id
     * @param string $old_password
     * @param string $password
     * @param string $trade_pass
     * @param string $old_trade_pass
     */
    public function changePassword(int $user_id, string $old_password, string $password, string $trade_pass, string $old_trade_pass)
    {
        $user = $this->userDAO->getUserById($user_id);

        if ($old_password !== '' && $password === '') {
            $this->error('validation.Account.ChangePasswordRequest.password.required');
        }

        if ($password !== '') {
            // 判断旧密码
            if (!password_verify($old_password, $user->password)) {
                $this->error('logic.OLD_PASS_ERROR');
            }

            // 判断新密码
            if (password_verify($password, $user->password)) {
                $this->error('logic.NEW_PASS_IS_SAME_AS_OLD_PASS');
            };


            // 修改新密码
            $user->password = $password;
        }

        // 输入原取款密码 新取款密码为空
        if ($old_trade_pass !== '' && $trade_pass === '') {
            $this->error('validation.Account.ChangePasswordRequest.trade_pass.alpha_dash');
        }

        // 输入新取款密码
        if ($trade_pass !== '') {
            if (!$user->trade_pass) {
                $this->error('logic.INCOMPLETE_PERSONAL_DATA');
            }

            if (!password_verify($old_trade_pass, $user->trade_pass)) {
                $this->error('logic.TRADE_PASS_ERROR');
            }
            $user->trade_pass = $trade_pass;
        }
        $user->save();
    }

    /**
     * 修改身份证
     *
     * @param int $user_id
     * @param string $id_card
     */
    public function changeIdCard(int $user_id, string $id_card)
    {
        // 检查身份证是否重复
        if ($this->container->get(UserInfoDAO::class)->checkColumnExisted($user_id, 'id_card', $id_card)) {
            $this->error('logic.ID_CARD_IS_EXIST');
        }

        // 修改用户信息身份证
        $this->container->get(UserInfoDAO::class)->update($user_id, [
            'id_card' => $id_card
        ]);
    }

    /**
     * 修改用户性别
     *
     * @param int $user_id
     * @param int $gender
     */
    public function changeGender(int $user_id, int $gender)
    {
        $user = $this->userDAO->getUserById($user_id);

        if ($gender === $user->gender) return;

        $user->gender = $gender;
        $user->save();
    }
}