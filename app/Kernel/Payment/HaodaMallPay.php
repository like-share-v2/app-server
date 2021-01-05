<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version   1.0.0
 * @link
 */

namespace App\Kernel\Payment;

use Hyperf\Guzzle\ClientFactory;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * HaodaMallPay
 *
 *
 * @package App\Kernel\Payment
 */
class HaodaMallPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://pre.pay.haodamall.com/';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ClientFactory
     */
    private $guzzle;

    /**
     * GagaPay constructor.
     *
     * @param ContainerInterface $container
     * @param ClientFactory      $guzzle
     */
    public function __construct(ContainerInterface $container, ClientFactory $guzzle)
    {
        $this->container = $container;
        $this->guzzle    = $guzzle;
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param array  $extra
     *
     * @return mixed
     */
    public function pay(string $pay_no, float $amount, array $extra = [])
    {
        $params         = [
            'mchtId'       => env('HAODA_PAY_ID'),
            'appId'        => env('HAODA_PAY_APPID'),
            'version'      => '20',
            'biz'          => 'ca001',
            'orderId'      => $pay_no,
            'orderTime'    => date('YmdHis'),
            'amount'       => $amount * 100,
            'currencyType' => 'INR',
            'goods'        => 'recharge',
            'notifyUrl'    => config('app_host') . 'v1/notify/haoda_pay',
        ];
        $params['sign'] = $this->getSign($params, env('HAODA_PAY_KEY'));
        try {
            $this->container->get(CacheInterface::class)->set($pay_no, [
                'method'   => 'post',
                'action'   => self::BASE_URL . 'gateway/cashier/mchtPay',
                'formData' => $params
            ], 60 * 5);
        }
        catch (InvalidArgumentException $e) {
        }

        return [
            'payUrl' => config('app_host') . 'v1/public/rechargeView?pay_no=' . $pay_no
        ];
    }

    /**
     * 验证签名
     *
     * @param array  $data
     * @param string $sign
     */
    public function verifySign(array $data, string $sign)
    {
        // TODO: Implement verifySign() method.
    }

    /**
     * 获取签名
     *
     * @param array  $data
     * @param string $key
     *
     * @return string
     */
    public function getSign(array $data, string $key)
    {
        unset($data['mchtId']);
        unset($data['version']);
        unset($data['biz']);
        ksort($data);
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}