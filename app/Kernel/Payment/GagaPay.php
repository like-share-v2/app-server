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
 * GagaPay
 *
 *
 * @package App\Kernel\Payment
 */
class GagaPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'http://www.mesongogo.com/';

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
        $params  = [
            'merchant'   => env('GAGA_PAY_MERCHANT', ''),
            'outTradeNo' => $pay_no,
            'type'       => $extra['type'],
            'money'      => $amount,
            'time'       => time(),
            'notifyUrl'  => env('HOST') . 'notify/gaga_pay',
            // 'channelName' => 'CG_EASEBUZZ'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/orderApi/new', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => array_merge($params, [
                'sign' => $this->getSign($params, env('GAGA_PAY_KEY', ''))
            ])
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 'success') {
            throw new LogicException($data['message']);
        }
        return $data;
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
    private function getSign(array $data, string $key)
    {
        ksort($data);
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}