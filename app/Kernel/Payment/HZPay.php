<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use Carbon\Carbon;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;

/**
 * HZPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class HZPay
{
    /**
     * @var string
     */
    const BASE_URL = 'http://lrznvm.fakgt.com/';

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
        $params         = [
            'mer_no'       => env('HZ_PAY_ID'),
            'mer_order_no' => $pay_no,
            'pname'        => $extra['name'],
            'pemail'       => $extra['email'],
            'phone'        => $extra['phone'],
            'order_amount' => $amount,
            'countryCode'  => getConfig('HzPayCountryCode', 'THA'),
            'ccy_no'       => getConfig('HzPayCcyNo', 'THB'),
            'busi_code'    => getConfig('HzPayBusiCode', '100202'),
            'goods'        => 'recharge',
            'notifyUrl'    => config('app_host') . 'v1/notify/hzpay'
        ];
        $params['sign'] = $this->getSign($params, env('HZ_PAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'ty/orderPay', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 'SUCCESS') {
            throw new LogicException($data['err_msg']);
        }

        return [
            'payUrl' => $data['order_data']
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

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }

    /**
     * 代付查询
     *
     * @param string $order_no
     *
     * @return mixed
     */
    public function defrayQuery(string $order_no)
    {
        $params         = [
            'request_no'   => (string)$this->container->get(IdGeneratorInterface::class)->generate(),
            'request_time' => Carbon::now('Asia/Bangkok')->format('YmdHis'),
            'mer_no'       => env('HZ_PAY_ID'),
            'mer_order_no' => $order_no,
        ];
        $params['sign'] = $this->getSign($params, env('HZ_PAY_KEY'));

        $request = $this->guzzle->create()->post('http://sujary.fakgt.com/withdraw/singleQuery', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['query_status'] ?? '') !== 'SUCCESS') {
            throw new LogicException($data['query_err_msg']?? '');
        }

        return $data;
    }
}