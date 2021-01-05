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
 * YT2Pay
 *
 * @author
 * @package App\Kernel\Payment
 */
class YT2Pay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://www.mixyd.com/';

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
        $pay_code        = getConfig('YtPayPayCode', '905');
        $params          = [
            'code'         => env('YT2_PAY_ID'),
            'merordercode' => $pay_no,
            'notifyurl'    => env('H5_HOST'),
            'callbackurl'  => config('app_host') . 'v1/notify/yt2pay',
            'amount'       => $amount,
            'paycode'      => $pay_code,
            'starttime'    => time() * 1000,
            'name'         => $extra['name'],
            'mobile'       => $extra['phone'],
            'email'        => $extra['email'],
        ];
        $params['signs'] = $this->getSign($params, env('YT2_PAY_KEY'));

        $uri = '';
        if ($pay_code === '905') {
            $uri = 'api/outer/icic/createOrder';
        }
        if ($pay_code === '904') {
            $uri = 'api/outer/collections/addOrderByLndia';
        }

        $request = $this->guzzle->create()->post(self::BASE_URL . $uri, [
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
     * 获取签名
     *
     * @param array  $data
     * @param string $key
     *
     * @return string
     */
    public function getSign(array $data, string $key)
    {
        unset($data['paycode']);
        unset($data['starttime']);
        unset($data['ipaddr']);
        unset($data['name']);
        unset($data['mobile']);
        unset($data['email']);
        unset($data['refNo']);
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}