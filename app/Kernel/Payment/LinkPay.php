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
 * LinkPay
 *
 *
 * @package App\Kernel\Payment
 */
class LinkPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://linkpay.surperpay.com/';

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
        $params             = [
            'version'     => '1.0',
            'charset'     => 'UTF-8',
            'spid'        => env('KINK_PAY_ID'),
            'spbillno'    => $pay_no,
            'lang'        => 'eng',
            'country'     => 'NG',
            'currency'    => 'NGN',
            'tranAmt'     => $amount * 100,
            'backUrl'     => '',
            'notifyUrl'   => config('app_host') . 'v1/notify/link_pay',
            'productName' => 'vip',
        ];
        $params['sign']     = $this->getSign($params, env('LINK_PAY_KEY'));
        $params['signType'] = 'MD5';

        $request = $this->guzzle->create()->post(self::BASE_URL . 'trade/unifiedOrder', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body'    => Json::encode($params)
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['retcode'] ?? '') !== '00000') {
            throw new LogicException($data['retmsg']);
        }

        return [
            'payUrl' => $data['url']
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
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}