<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Request\Account\ChangeAvatarRequest;
use App\Request\Account\ChangeBankRequest;
use App\Request\Account\ChangeGenderRequest;
use App\Request\Account\ChangeIdCardRequest;
use App\Request\Account\ChangeNicknameRequest;
use App\Middleware\AuthMiddleware;
use App\Request\Account\ChangePasswordRequest;
use App\Request\Account\ChangePhoneRequest;
use App\Service\AccountService;
use App\Service\Dao\CountryBankDAO;
use App\Service\Dao\UserBillDAO;
use App\Service\Dao\UserInfoDAO;
use App\Service\Dao\UserLevelDAO;
use App\Service\Dao\UserMemberDAO;
use App\Service\Dao\UserRelationDAO;
use App\Service\Dao\UserTaskDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 账号控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class AccountController extends AbstractController
{
    /**
     * 用户资料
     *
     * @GetMapping(path="info")
     */
    public function info()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $user->makeHidden([
            'password'
        ]);

        //        $user_level_list  = array_column($this->container->get(UserLevelDAO::class)->getAllList()->toArray(), 'name', 'level');

        //        $user->level_name = $user_level_list[$user->level];

        $level_name = [];
        foreach ($user->userMember as $user_member) {
            if ($user_member->getAttributes()['effective_time'] > time() || $user_member->getAttributes()['effective_time'] === -1) {
                $level_name[] = $user_member->userLevel->name;
            }
        }
        $level_name = implode(',', $level_name);

        $user->level_name = $level_name;
        $this->success($user);
    }

    /**
     * 修改昵称
     *
     * @PutMapping(path="nickname")
     * @param ChangeNicknameRequest $request
     */
    public function changeNickname(ChangeNicknameRequest $request)
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $params  = $request->all();

        $this->container->get(AccountService::class)->changeNickname($user_id, $params['nickname']);

        $this->success();
    }

    /**
     * 修改头像
     *
     * @PutMapping(path="avatar")
     * @param ChangeAvatarRequest $request
     */
    public function changeAvatar(ChangeAvatarRequest $request)
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $avatar  = (string)$request->input('avatar');

        $this->container->get(AccountService::class)->changeAvatar($user_id, $avatar);
        $this->success();
    }

    /**
     * 修改手机号
     *
     * @PutMapping(path="phone")
     * @param ChangePhoneRequest $request
     */
    public function changePhone(ChangePhoneRequest $request)
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $phone   = (string)$request->input('phone');
        $code    = (string)$request->input('code');
        $add_num = $this->request->input('add_num', '');

        if ($add_num === '') {
            $this->error('logic.PLEASE_SELECT_COUNTRY', 4000);
        }

        $this->container->get(AccountService::class)->changePhone($user_id, $phone, $code, $add_num);
        $this->success();
    }

    /**
     * 修改密码
     *
     * @PutMapping(path="password")
     * @param ChangePasswordRequest $request
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user_id        = JwtInstance::instance()->build()->getId();
        $old_password   = (string)$request->input('old_password', '');
        $password       = (string)$request->input('password', '');
        $trade_pass     = (string)$request->input('trade_pass', '');
        $old_trade_pass = (string)$request->input('old_trade_pass', '');

        if (($old_password === '' && $password === '') && ($trade_pass === '' && $old_trade_pass === '')) {
            $this->error('validation.Account.ChangePasswordRequest.old_password.required');
        }

        $this->container->get(AccountService::class)->changePassword($user_id, $old_password, $password, $trade_pass, $old_trade_pass);
        $this->success();
    }

    /**
     * 修改身份证号码
     *
     * @PutMapping(path="id_card")
     * @param ChangeIdCardRequest $request
     */
    public function changeIdCard(ChangeIdCardRequest $request)
    {
        $user_id = JwtInstance::instance()->build()->getId();
        $id_card = (string)$request->input('id_card');

        $this->container->get(AccountService::class)->changeIdCard($user_id, $id_card);
        $this->success();
    }

    /**
     * 修改银行信息
     *
     * @PutMapping(path="bank")
     * @param ChangeBankRequest $request
     */
    public function changeBank(ChangeBankRequest $request)
    {
        $user = JwtInstance::instance()->build()->getUser();

        $name       = trim((string)$request->input('name', ''));
        $account    = trim((string)$request->input('account', ''));
        $trade_pass = trim((string)$request->input('trade_pass', ''));
        $phone      = trim($this->request->input('phone', ''));
        $bank_code  = $this->request->input('bank_code', '');
        $email      = trim($this->request->input('email', ''));
        $upi        = trim($this->request->input('upi', ''));
        $ifsc       = trim($this->request->input('ifsc', ''));

        // 不允许重复修改
        if ($user->info->bank_code) {
            $this->error('logic.PLEASE_CONTACT_CUSTOMER_EDIT');
        }

        // 查找银行
        if ($bank_code !== '' && !$bank = $this->container->get(CountryBankDAO::class)->getBankByBankCode($bank_code)) {
            $this->error('logic.PLEASE_RESELECT_BANK');
        }
        // 检查银行卡账号
        if ($this->container->get(UserInfoDAO::class)->checkColumnExisted($user->id, 'account', $account)) {
            $this->error('logic.BANK_ACCOUNT_IS_EXIST');
        }
        if ($this->container->get(UserInfoDAO::class)->checkColumnExisted($user->id, 'name', $name)) {
            $this->error('logic.BANK_ACCOUNT_IS_EXIST');
        }

        $user->trade_pass = $trade_pass;
        $user->save();
        // 修改用户信息
        $this->container->get(UserInfoDAO::class)->update($user->id, [
            'bank_name' => $bank->name ?? '',
            'name'      => $name,
            'account'   => $account,
            'bank_code' => $bank->code ?? '',
            'phone'     => $phone,
            'email'     => $email,
            'upi'       => $upi,
            'ifsc'      => $ifsc
        ]);

        $this->success();
    }

    /**
     * 修改性别
     *
     * @PutMapping(path="gender")
     * @param ChangeGenderRequest $request
     */
    public function changeGender(ChangeGenderRequest $request)
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $gender = (int)$request->input('gender', 0);

        $this->container->get(AccountService::class)->changeGender($user_id, $gender);

        $this->success();
    }

    /**
     * 团队信息
     *
     * @GetMapping(path="index")
     */
    public function index()
    {
        $user = JwtInstance::instance()->build()->getUser();

        // 任务收益
        $task_profit = $this->container->get(UserBillDAO::class)->getTaskProfitByUserId($user->id);

        // 团队收益
        $team_profit = $this->container->get(UserBillDAO::class)->getTeamProfitByUserId($user->id);

        // 团队人数
        $team_count = $this->container->get(UserRelationDAO::class)->getLowersCount($user->id);

        // 今日剩余可领取任务数
        // $level_task_count   = $this->container->get(UserLevelDAO::class)->findByLevel($user->level)->task_num;
        // $last_receive_count = $level_task_count - $this->container->get(UserTaskDAO::class)->getUserTodayTaskCount($user->id);


        $this->success([
            'task_profit' => $task_profit,
            'team_profit' => $team_profit,
            'team_count'  => $team_count,
            // 'receive_count' => $last_receive_count
        ]);
    }

    /**
     * @GetMapping(path="member_data")
     */
    public function getAccountMemberData()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $user_member_data = $this->container->get(UserMemberDAO::class)->getByUserId($user_id)->toArray();
        foreach ($user_member_data as &$user_member) {
            if ($user_member['user_level'] === 0) {
                $user_member['receive_count'] = 0;
            }
            else {
                $today_receive_count = $this->container->get(UserTaskDAO::class)->getUserTodayTaskCountByLevel($user_id, $user_member['level']);
                // var_dump([$today_receive_count, $user_member['level']]);
                $user_member['receive_count'] = $user_member['user_level']['task_num'] - $today_receive_count;
            }

            if (strtotime($user_member['effective_time']) < time()) {
                $user_member['receive_count'] = '-';
            }
        }

        $this->success($user_member_data);
    }
}