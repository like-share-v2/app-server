<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;

/**
 * HZPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class ShineUPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://testgateway.shineupay.com/';

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

    public function pay(string $pay_no, float $amount, array $extra = [])
    {
        $params = [
            'merchantId' => env('SHINEUPAY_ID'),
            'timestamp'  => (string)(time() * 1000),
            'body'       => [
                'amount'    => $amount,
                'orderId'   => $pay_no,
                'details'   => 'recharge',
                'userId'    => $extra['phone'],
                'notifyUrl' => config('app_host') . 'v1/notify/shineupay',
            ]
        ];

        $request = $this->guzzle->create()->post(self::BASE_URL . 'pay/create', [
            'headers' => [
                'Api-Sign'     => $this->getSign($params, env('SHINEUPAY_KEY')),
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 0) {
            throw new LogicException($data['message']);
        }

        return [
            'payUrl' => $data['body']['content']
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
    public function getSign(array $data, string $key): string
    {
        return md5(Json::encode($data) . '|' . $key);
    }
}