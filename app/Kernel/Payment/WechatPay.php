<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Payment\Application;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Overtrue\Socialite\Providers\AbstractProvider;
use Psr\Container\ContainerInterface;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use function EasyWeChat\Kernel\Support\generate_sign;

/**
 * 微信支付
 *
 *
 * @package App\Kernel\Payment
 */
class WechatPay implements PayInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Application
     */
    protected $payment;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * WechatPay constructor.
     *
     * @param ContainerInterface $container
     * @param LoggerFactory $logger
     */
    public function __construct(ContainerInterface $container, LoggerFactory $logger)
    {
        $this->container = $container;
        $this->logger    = $logger->get('log', 'wechat_pay');
        AbstractProvider::setGuzzleOptions([
            'http_errors' => false,
            'handler'     => HandlerStack::create(new CoroutineHandler()),
        ]);
        $payment           = Factory::payment(config('wechat.payment'));
        $config            = $payment['config']->get('http', []);
        $config['handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $payment->rebind('http_client', new Client($config));
        $cache = $this->container->get(CacheInterface::class);
        $payment->rebind('cache', $cache);
        $payment['guzzle_handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $this->payment             = $payment;
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float $amount
     * @param array $extra
     * @return mixed
     */
    public function pay(string $pay_no, float $amount, array $extra = [])
    {
        try {
            $key    = $this->payment->getKey();
            $result = $this->payment->order->unify([
                'body'             => $extra['body'],
                'out_trade_no'     => $pay_no,
                'total_fee'        => $amount * 100,
                'spbill_create_ip' => $this->container->get(RequestInterface::class)->getServerParams()['remote_addr'] ?? '',
                'notify_url'       => env('HOST', '') . '/v1/notify/wechatPaidNotify',
                'trade_type'       => 'JSAPI',
                'openid'           => $extra['openid']
            ]);
        } catch (GuzzleException $e) {
            throw new LogicException('logic.SERVER_ERROR');
        } catch (\Exception $e) {
            throw new LogicException('logic.SERVER_ERROR');
        }

        $time                   = time();
        $nonceStr               = uniqid();
        $result['PaySign']      = generate_sign([
            'appId'     => $result['appid'],
            'timeStamp' => $time,
            'nonceStr'  => $nonceStr,
            'package'   => 'prepay_id=' . $result['prepay_id'],
            'signType'  => 'MD5'
        ], $key);
        $result['PayTimeStamp'] = (string)$time;
        $result['PayNonceStr']  = $nonceStr;

        return $result;
    }

    /**
     * 验证签名
     *
     * @param array $data
     * @param string $sign
     * @throws InvalidArgumentException
     */
    public function verifySign(array $data, string $sign)
    {
        unset($data['sign']);
        // 判断签名
        if (generate_sign($data, $this->payment->getKey()) !== $sign) {
            throw new LogicException('签名不正确');
        }
    }
}