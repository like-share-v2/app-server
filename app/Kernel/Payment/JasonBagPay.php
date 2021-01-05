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
 * JasonBagPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class JasonBagPay
{
    /**
     * @var string
     */
    const BASE_URL = 'http://api.jasonbag.com/';

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
            'agentNo'     => env('JASON_BAG_PAY_ID'),
            'timestamp'   => time() * 1000,
            'orderNo'     => $pay_no,
            'amount'      => $amount * 100,
            'paymentType' => 'BANK',
            'notifyUrl'   => config('app_host') . 'v1/notify/jason_bag_pay'
        ];
        $params['sign'] = $this->getSign($params, env('JASON_BAG_PAY_KEY'));
        $request        = $this->guzzle->create()->post(self::BASE_URL . 'payment', [
            'form_params' => $params
        ]);
        $data           = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 200) {
            throw new LogicException($data['msg']);
        }

        return [
            'payUrl' => $data['data']
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
        unset($data['sign']);
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return md5(urldecode(http_build_query($data) . '&key=' . $key));
    }
}