<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version   1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Captcha\SMSCaptcha;
use App\Service\Dao\UserDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Psr\SimpleCache\InvalidArgumentException;
use Zunea\HyperfKernel\Service\SMSService;
use Zunea\HyperfKernel\SMS\Exception\SMSIntervalException;
use Zunea\HyperfKernel\Utils\Random;

/**
 * 短信控制器
 *
 * @Controller()
 *
 * @package App\Controller\v1
 */
class SmsController extends AbstractController
{
    /**
     * 发送验证码
     *
     * @param string $phone
     */
    public function sendCode(string $phone)
    {
        $type = $this->request->input('type', 1);

        // 判断验证码类型
        if (!in_array($type, [1, 2, 3, 4])) {
            $this->error('logic.SERVER_ERROR');
        }

        // 判断手机号
        if (!preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            $this->error('logic.PHONE_NUMBER_ERROR');
        }

        $sign_name = env('ALI_CLOUD_SMS_SIGN');

        switch ($type) {
            // 登录验证码
            case 1:
                $prefix = SMSCaptcha::LOGIN_CAPTCHA_PREFIX;
                // 登录模板和登录模板签名
                $template_code = env('ALI_CLOUD_SMS_LOGIN_TEMPLATE_CODE');
                break;
            // 更改手机号验证码
            case 2:
                $prefix        = SMSCaptcha::PHONE_CAPTCHA_PREFIX;
                $template_code = env('ALI_CLOUD_SMS_CHANGE_PHONE_TEMPLATE_CODE');
                break;
            // 注册
            case 3:
                $prefix        = SMSCaptcha::REGISTER_CAPTCHA_PREFIX;
                $template_code = env('ALI_CLOUD_SMS_REGISTER_TEMPLATE_CODE');
                break;
            // 忘记密码
            case 4:
                $prefix        = SMSCaptcha::RESET_PASS_CAPTCHA_PREFIX;
                $template_code = env('ALI_CLOUD_SMS_RESET_PASS_TEMPLATE_CODE');
                break;
            default:
                $prefix        = '';
                $template_code = '';
                break;
        }

        // 设置验证码
        $this->container->get(SMSCaptcha::class)->set($phone, 60, $prefix, $template_code, $sign_name);

        $this->success();
    }

    /**
     * 发送短信
     *
     * @param string $phone
     */
    public function sendCode2(string $phone)
    {
        $type = $this->request->input('type', 1);

        // 判断验证码类型
        if (!in_array($type, [1, 2, 3, 4])) {
            $this->error('logic.SERVER_ERROR', 4000);
        }

        // 判断手机号
        if (!preg_match("/^1[3456789]{1}\d{9}$/", $phone)) {
            $this->error('logic.PHONE_NUMBER_ERROR', 4000);
        }

        switch ($type) {
            // 登录验证码
            case 1:
                $scene = 'login';
                // 登录模板
                $template_code = '222130';
                // 查找手机号
                if (!$this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_NUMBER_NOT_FOUND', 4000);
                }
                break;
            // 更改手机号验证码
            case 2:
                $scene         = 'change_phone';
                $template_code = '';
                break;
            // 注册
            case 3:
                $scene         = 'register';
                $template_code = '222117';
                if ($this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_REGISTERED', 4000);
                }
                break;
            // 重置密码
            case 4:
                $scene         = 'reset_pass';
                $template_code = '222131';
                if (!$this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_NUMBER_NOT_FOUND', 4000);
                }
                break;
            default:
                $scene         = '';
                $template_code = '';
                break;
        }

        $code = Random::generatorCode6();

        try {
            $this->container->get(SMSService::class)->setChannel('juhe')->sendVerifyCode($phone, $scene, $code, $template_code);
        }
        catch (SMSIntervalException $e) {
            if ($e->getMessage() === 'SMS is sent too frequently') {
                $this->error('logic.FREQUENT_SMS_REQUESTS', 4000);
            }

            $this->error('logic.SERVER_ERROR', 4000);
        }

        $this->success();
    }

    /**
     * 发送短信
     *
     * @GetMapping(path="sendCode/{phone}")
     * @param string $phone
     */
    public function sendCode3(string $phone)
    {
        $type    = $this->request->input('type', 1);
        $add_num = $this->request->input('add_num', '');
        if ($add_num === '') {
            $this->error('logic.PLEASE_SELECT_COUNTRY', 4000);
        }

        // 判断验证码类型
        if (!in_array($type, [1, 2, 3, 4, 5, 6])) {
            $this->error('logic.SERVER_ERROR', 4000);
        }

        $code = Random::generatorCode6();

        switch ($type) {
            // 登录验证码
            case 1:
                $scene = 'login';
                // 登录内容
                $content = __('logic.LOGIN_SMS_CONTENT', ['code' => $code]);
                // 查找手机号
                if (!$this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_NUMBER_NOT_FOUND');
                }
                break;

            // 更改手机号验证码
            case 2:
                $scene   = 'change_phone';
                $content = __('logic.RESET_PASS_SMS_CONTENT', ['code' => $code]);
                if ($this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_REGISTERED');
                }
                break;

            // 注册
            case 3:
                $scene   = 'register';
                $content = getConfig('register_sms_content', 'Your verification code is $code');
                $content = str_replace('$code', $code, $content);
                if ($this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_REGISTERED');
                }
                break;

            // 重置密码
            case 4:
                $scene   = 'reset_pass';
                $content = __('logic.RESET_PASS_SMS_CONTENT', ['code' => $code]);
                if (!$this->container->get(UserDAO::class)->checkValueIsUsed('phone', $phone)) {
                    $this->error('logic.PHONE_NUMBER_NOT_FOUND');
                }
                break;

            case 5:
                // 后台登录
                $scene   = 'admin_login';
                $content = __('logic.LOGIN_SMS_CONTENT', ['code' => $code]);
                break;

            case 6:
                // 代理登录
                $scene   = 'agent_login';
                $content = __('logic.LOGIN_SMS_CONTENT', ['code' => $code]);
                break;

            default:
                $scene   = '';
                $content = '';
                break;
        }


        try {
            $content = getConfig('sms_sign', '') . $content;
            $this->container->get(\App\Service\SMSService::class)->sendVerifyCode($phone, $add_num, $scene, $code, $content);
        }
        catch (\Throwable $e) {
            var_dump($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
        catch (InvalidArgumentException $e) {
        }

        $this->success();
    }
}