<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\SMS\SMSFactory;

use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\SimpleCache\InvalidArgumentException;
use Zunea\HyperfKernel\SMS\Exception\SMSException;
use Zunea\HyperfKernel\SMS\Exception\SMSIntervalException;

/**
 * 短信服务
 *
 *
 * @package App\Service
 */
class SMSService extends Base
{
    /**
     * @Inject()
     * @var ClientFactory
     */
    private $guzzle;

    /**
     * 发送短信验证码
     *
     * @param string $mobile
     * @param string $code
     * @param string $template
     * @param string $sign_name
     *
     * @return mixed
     */
    public function sendCode(string $mobile, string $code, string $template, string $sign_name)
    {
        return di(SMSFactory::class)->getAliCloud()->sendSMS($mobile, json_encode(['code' => $code]), $template, $sign_name);
    }

    /**
     * 发送短信
     *
     * @param string $mobile  发送手机号
     * @param string $content 发送内容
     * @param string $template
     * @param string $sign_name
     *
     * @return mixed
     */
    public function sendSMS(string $mobile, string $content, string $template, string $sign_name)
    {
        return di(SMSFactory::class)->getAliCloud()->sendSMS($mobile, $content, $template, $sign_name);
    }

    /**
     * 发送验证码
     *
     * @param string $phone   手机号码
     * @param string $add_num 国际区号
     * @param string $scene   场景
     * @param string $code    验证码
     * @param string $content 短信内容
     *
     * @throws SMSException
     * @throws SMSIntervalException
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function sendVerifyCode(string $phone, string $add_num, string $scene, string $code, string $content)
    {
        $phone = $add_num . $phone;

        // 获取缓存
        $cacheName = sprintf(config('sms.verify_code_cache'), $scene, $phone);
        try {
            if (($his = $this->cache->get($cacheName, null)) !== null) {
                // 是否开启发送频率限制
                if (($interval = config('sms.interval', 0)) > 0) {
                    // 判断发送频率
                    if (isset($his['setTime']) && $his['setTime'] + $interval > time()) {
                        throw new SMSIntervalException('SMS is sent too frequently');
                    }
                }
            }
            // 发送验证码
            $result = $this->send($phone, $add_num, $content);
            $this->cache->set($cacheName, [
                'code'    => $code,
                'setTime' => time()
            ], config('sms.expired'));
            return $result;
        }
        catch (\Throwable $e) {
            throw new SMSException('Failed to send:' . $e->getMessage());
        }
    }

    /**
     * 发送短信
     *
     * @param string $phoneNumber
     * @param string $add_num
     * @param string $content
     *
     * @return array
     */
    public function send(string $phoneNumber, string $add_num, string $content): array
    {
        $guzzle = $this->guzzle->create();
        try {
            switch (getConfig('SMSChannel', 'skylinelabs')) {
                case 'skylinelabs':
                    $th_account  = getConfig('SMSThAccount', env('TH_SMS_ACCOUNT'));
                    $th_password = getConfig('SMSThPassword', env('TH_SMS_PASSWORD'));
                    $date        = Carbon::now('Asia/Shanghai')->addSeconds(30 * 60)->format('YmdHis');
                    $response    = $guzzle->get('http://sms.skylinelabs.cc:20003/sendsmsV2', [
                        'query' => [
                            'version'  => '1.0',
                            'account'  => $th_account,
                            'datetime' => $date,
                            'numbers'  => $phoneNumber,
                            'content'  => $content,
                            'sign'     => md5($th_account . $th_password . $date)
                        ]
                    ]);
                    if ($response->getStatusCode() !== 200) {
                        throw new SMSException(sprintf('Response status code is abnormal: %s', $response->getStatusCode()));
                    }
                    $responseContents = $response->getBody()->getContents();
                    $result           = Json::decode($responseContents, true);
                    if (!isset($result['status']) || (int)$result['status'] !== 0) {
                        throw new SMSException(sprintf('SMS failed to send, return result: %s', $responseContents));
                    }
                    break;

                case 'chuanglan':
                    $response = $guzzle->get('https://intapi.253.com/send/json', [
                        'json' => [
                            'account'  => getConfig('SMSchuanglanAccount', env('CHUANGLAN_ACCOUNT')),
                            'password' => getConfig('SMSchuanglanPassword', env('CHUANGLAN_PASSWORD')),
                            'mobile'   => $phoneNumber,
                            'msg'      => $content,
                        ]
                    ]);
                    if ($response->getStatusCode() !== 200) {
                        throw new SMSException(sprintf('Response status code is abnormal: %s', $response->getStatusCode()));
                    }
                    $responseContents = $response->getBody()->getContents();
                    $result           = Json::decode($responseContents, true);
                    if (!isset($result['code']) || (int)$result['code'] !== 0) {
                        throw new SMSException(sprintf('SMS failed to send, return result: %s', $responseContents));
                    }
                    break;

                case 'chuanxin':
                    $appkey    = getConfig('SMSChuangxinAppkey');
                    $appsecret = getConfig('SMSChuangxinAppsecret');
                    $appcode   = getConfig('SMSChuangxinAppcode');
                    $response  = $guzzle->get('http://47.242.85.7:9090/sms/batch/v2', [
                        'headers' => [
                            'Content-Type' => 'application/json; charset=utf-8'
                        ],
                        'query'   => [
                            'appkey'    => $appkey,
                            'appcode'   => $appcode,
                            'appsecret' => $appsecret,
                            'sign'      => md5($appkey . $appsecret . time()),
                            'phone'     => $phoneNumber,
                            'msg'       => $content,
                        ]
                    ]);
                    if ($response->getStatusCode() !== 200) {
                        throw new SMSException(sprintf('Response status code is abnormal: %s', $response->getStatusCode()));
                    }
                    $responseContents = $response->getBody()->getContents();
                    $result           = Json::decode($responseContents, true);
                    if (!isset($result['code']) || $result['code'] !== '00000') {
                        throw new SMSException(sprintf('SMS failed to send, return result: %s', $responseContents));
                    }
                    break;

                default:
                    throw new LogicException('SMS failed to send');
            }
            return $result;
        }
        catch (\Exception $e) {
            throw new SMSException(sprintf('ServerException: %s', $e->getMessage()), $e->getCode());
        }
    }
}