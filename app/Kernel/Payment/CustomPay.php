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
 * CustomPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class CustomPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://www.geejjian.com/';

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
        $amount                = number_format($amount, 2, '.', '');
        $params                = [
            'pay_memberid'    => env('CUSTOM_PAY_ID'),
            'pay_orderid'     => $pay_no,
            'pay_amount'      => $amount,
            'pay_applydate'   => date('Y-m-d H:i:s'),
            'pay_bankcode'    => '912',
            'pay_notifyurl'   => config('app_host') . 'v1/notify/custom_pay',
            'pay_callbackurl' => env('H5_HOST'),
            'pay_returntype'  => 2,
        ];
        $params['pay_md5sign'] = $this->getSign($params, env('CUSTOM_PAY_KEY'));
        $params['pay_email']   = $extra['email'];
        $params['pay_mobile']  = $extra['phone'];
        $params['pay_name']    = $extra['name'];
        $request               = $this->guzzle->create()->post(self::BASE_URL . 'Pay_Index.html', [
            'form_params' => $params
        ]);
        $data                  = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') != 'success') {
            throw new LogicException($pay_no . ':' . $data['success']);
        }

        return [
            'payUrl' => $data['data']['pay_url']
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
        unset($data['pay_returntype']);
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}