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
 * ZFPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class ZFPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://api.zf77777.org/';

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
     * @param string $pay_no
     * @param float  $amount
     *
     * @return array
     */
    public function pay(string $pay_no, float $amount): array
    {
        $params         = [
            'userid'    => env('ZF_PAY_ID'),
            'orderid'   => $pay_no,
            'type'      => getConfig('ZFPayType', 'paytm'),
            'amount'    => $amount,
            'notifyurl' => config('app_host') . 'v1/notify/zf_pay',
        ];
        $params['sign'] = $this->getSign($params, env('ZF_PAY_KEY'));
        $request        = $this->guzzle->create()->post(self::BASE_URL . 'api/create', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data           = Json::decode($request->getBody()->getContents(), true);
        if (($data['success'] ?? 0) !== 1) {
            throw new LogicException($data['message']);
        }

        return [
            'payUrl' => $data['pageurl']
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
        return md5($key . $data['orderid'] . $data['amount']);
    }
}