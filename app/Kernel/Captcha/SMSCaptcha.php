<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\Captcha;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\Utils\Random;
use App\Service\SMSService;

use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 短信验证码逻辑
 *
 *
 * @package App\Kernel\Captcha
 */
class SMSCaptcha extends Base
{
    /**
     * 登陆验证码缓存前缀
     *
     * @var string
     */
    const LOGIN_CAPTCHA_PREFIX = 'LoginSMSCode:';

    /**
     * 修改手机号验证码缓存前缀
     *
     * @var string
     */
    const PHONE_CAPTCHA_PREFIX = 'PhoneSMSCode:';

    /**
     * 注册验证码缓存前缀
     *
     * @var string
     */
    const REGISTER_CAPTCHA_PREFIX = 'RegisterSMSCode:';

    /**
     * 注册验证码缓存前缀
     *
     * @var string
     */
    const RESET_PASS_CAPTCHA_PREFIX = 'ResetPassSMSCode:';

    /**
     * @Inject()
     * @var SMSService
     */
    private $SMSService;

    /**
     * 设置验证码
     *
     * @param string $mobile
     * @param int $limitSecond
     * @param string $prefix
     * @param string $template_code
     * @param string $sign_name
     * @return bool
     */
    public function set(string $mobile, int $limitSecond, string $prefix, string $template_code, string $sign_name): bool
    {
         try {
            // 判断发送间隔
            $his = $this->cache()->get($prefix . ':' . $mobile);
            if ($his && $his['setTime'] + $limitSecond > time()) {
                $this->error('logic.SMS_CODE_FREQUENTLY');
            }

            $code = Random::generatorCode6();

            /*$sms_result = $this->SMSService->sendCode($mobile, $code, $template_code, $sign_name);

            if ($sms_result['Code'] !== 'OK') {
                throw new LogicException($sms_result['Message']);
            }

             return $this->cache()->set($prefix . ':' . $mobile, [
                    'code' => $code,
                    'setTime' => time(),
             ], 1800);*/
             $this->cache()->set($prefix . ':' . $mobile, [
                 'code' => $code,
                 'setTime' => time(),
             ], 1800);
        }
        catch (InvalidArgumentException $e) {
            return false;
        }
        catch (LogicException $e) {
             $this->logger('SMS')->error($e->getMessage());
             $this->error('logic.SERVER_ERROR');
        }

        // 开发阶段直接返回验证码
        $this->success($code);
        return true;
    }

    /**
     * 获取验证码
     *
     * @param string $mobile
     * @param string $prefix
     * @return null|string
     */
    public function get(string $mobile, string $prefix): ?string
    {
        try {
            $code = $this->cache()->get($prefix . ':' . $mobile);

            return is_array($code) ? $code['code'] : null;
        }
        catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * 销毁验证码
     *
     * @param string $mobile
     * @param string $prefix
     * @return bool
     */
    public function del(string $mobile, string $prefix): bool
    {
        try {
            return (bool)$this->cache()->delete($prefix . ':' . $mobile);
        }
        catch (InvalidArgumentException $e) {
            return false;
        }
    }
}