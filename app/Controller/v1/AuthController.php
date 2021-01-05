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
use App\Request\Auth\ResetPasswordRequest;
use App\Request\Auth\LoginRequest;
use App\Request\Auth\PhoneLoginRequest;
use App\Request\Auth\RegisterRequest;
use App\Service\UserService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 登陆控制器
 *
 * @Controller()
 *
 * @package App\Controller
 */
class AuthController extends AbstractController
{
    /**
     * 登陆接口
     *
     * @GetMapping(path="login")
     * @param LoginRequest $request
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login(LoginRequest $request)
    {
        $phone = trim($request->input('account'));
        $password = trim($request->input('password'));

        $user = $this->container->get(UserService::class)->login($phone, $password);

        // 生成Token
        $token = JwtInstance::instance()->encode($user);

        $this->cache->set('user_id_' . $user->id . '_last_token', $token);

        $this->success([
            'token' => $token
        ]);
    }

    /**
     * 手机登录
     *
     * @PostMapping(path="phoneLogin")
     * @param PhoneLoginRequest $request
     */
     public function phoneLogin(PhoneLoginRequest $request)
    {
        $params = $request->all();

        $user = $this->container->get(UserService::class)->phoneLogin($params['phone'], $params['code']);

        // 生成Token sss
        $token = JwtInstance::instance()->encode($user);

        $this->success([
            'token' => $token
        ]);
    }

    /**
     * 注册
     *
     * @PostMapping(path="register")
     * @param RegisterRequest $request
     */
    public function register(RegisterRequest $request)
    {
        $params = $request->all();

        $user = $this->container->get(UserService::class)->register($params);

        $token = JwtInstance::instance()->encode($user);

        $this->success([
            'token' => $token
        ]);
    }

    /**
     * 忘记密码
     *
     * @PutMapping(path="resetPass")
     * @param ResetPasswordRequest $request
     */
    public function forgetPassword(ResetPasswordRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserService::class)->resetPassword($params);

        $this->success();
    }

    /**
     * 网页注册
     *
     * @PostMapping(path="html_register")
     * @param RegisterRequest $request
     */
    public function htmlRegister(RegisterRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserService::class)->register($params);

        $url = getConfig('app_download_url', '');

        $this->success(['url' => $url]);
    }
}