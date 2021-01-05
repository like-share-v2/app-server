<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\WechatFactory;

use GuzzleHttp\HandlerStack;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;
use Overtrue\Socialite\Providers\AbstractProvider;
use Psr\Container\ContainerInterface;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;

/**
 * PaymentFactory

 * @property \EasyWeChat\Payment\Order\Client                   $order
 * @package App\Kernel\WechatFactory
 */
class PaymentFactory
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \EasyWeChat\Payment\Application
     */
    public $payment;

    /**
     * MiniProgramFactory constructor.
     */
    public function __construct()
    {
        AbstractProvider::setGuzzleOptions([
            'http_errors' => false,
            'handler'     => HandlerStack::create(new CoroutineHandler()),
        ]);
    }

    /**
     * 注册微信支付
     */
    private function register()
    {
        $payment = Factory::payment(config('wechat.payment'));
        $config = $payment['config']->get('http', []);
        $config['handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $payment->rebind('http_client', new Client($config));
        $cache = $this->container->get(CacheInterface::class);
        $payment->rebind('cache', $cache);
        $payment['guzzle_handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $this->payment = $payment;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        $this->register();
        if ($this->payment->shouldDelegate($id)) {
            return $this->payment->delegateTo($id);
        }
        return $this->payment->offsetGet($id);
    }
}