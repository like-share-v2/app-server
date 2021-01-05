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
 * PopMode
 *
 * @author
 * @package App\Kernel\Payment
 */
class PopModePay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://api.popmode.in/';

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
        $params                    = [
            'pay_memberid'    => env('POP_MODE_PAY_ID'),
            'out_trade_no'    => $pay_no,
            'pay_applydate'   => date('Y-m-d H:i:s'),
            'pay_type'        => '965',
            'pay_notifyurl'   => config('app_host') . 'v1/notify/popmodepay',
            'pay_callbackurl' => env('H5_HOST'),
            'pay_amount'      => $amount
        ];
        $params['sign']            = $this->getSign($params, env('POP_MODE_PAY_KEY'));
        $params['customerName']    = $extra['name'];
        $params['customerPhone']   = $extra['phone'];
        $params['customerEmail']   = $extra['email'];
        $params['pay_productname'] = 'pay';

        try {
            $this->container->get(CacheInterface::class)->set($pay_no, [
                'method'   => 'post',
                'action'   => self::BASE_URL . 'Pay_Index.html',
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
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });
        ksort($data);

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
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
}