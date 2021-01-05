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
 * YTPay
 *
 *
 * @package App\Kernel\Payment
 */
class YTPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'http://www.ytzfyn.com/';

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
        $params              = [
            'code'         => env('YT_PAY_ID'),
            'merordercode' => $pay_no,
            'notifyurl'    => config('app_host'),
            'callbackurl'  => config('app_host') . 'v1/notify/yt_pay',
            'amount'       => $amount,
            'paycode'      => getConfig('YtPayPayCode', '905'),
        ];
        $params['signs']     = $this->getSign($params, env('YT_PAY_KEY'));
        $params['starttime'] = time() * 1000;

        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/outer/collections/addthailandPayOrder', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== '200') {
            throw new LogicException($data['msg']);
        }

        return [
            'payUrl' => $data['data']['checkstand']
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
        // ksort($data);
        // $data = array_filter($data, function ($item) {
        //     return $item !== '';
        // });
        unset($data['paycode']);
        var_dump(urldecode(http_build_query($data) . '&key=' . $key));
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}