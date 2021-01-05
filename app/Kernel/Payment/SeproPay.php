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
 * SeproPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class SeproPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://pay.sepropay.com/';

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

    public function pay(string $pay_no, float $amount)
    {
        $params              = [
            'mch_id'       => env('SEPRO_PAY_ID'),
            'notify_url'   => config('app_host') . 'v1/notify/sepropay',
            'mch_order_no' => $pay_no,
            'pay_type'     => getConfig('SeproPayType', 122),
            'trade_amount' => $amount,
            'order_date'   => date('Y-m-d H:i:s'),
            'bank_code'    => '',
            'goods_name'   => 'recharge'
        ];
        $params['sign']      = $this->getSign($params, env('SEPRO_PAY_KEY'));
        $params['sign_type'] = 'MD5';

        try {
            $this->container->get(CacheInterface::class)->set($pay_no, [
                'method'   => 'post',
                'action'   => self::BASE_URL . 'sepro/pay/web',
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
     * 获取签名
     *
     * @param array  $data
     * @param string $key
     *
     * @return string
     */
    public function getSign(array $data, string $key)
    {
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return md5(urldecode(http_build_query($data) . '&key=' . $key));
    }
}