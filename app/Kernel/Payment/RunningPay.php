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
 * RunningPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class RunningPay
{
    /**
     * @var string
     */
    const BASE_URL = 'http://loanmanager.in/';

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
        $amount     = number_format($amount, 2, '.', '');
        $merchantId = '999';
        $params     = [
            'merchantId'      => $merchantId,
            'merchantOrderId' => $pay_no,
            'amount'          => $amount,
            'timestamp'       => time() * 1000,
            'payType'         => 1,
            'notifyUrl'       => config('app_host') . 'v1/notify/running_pay',
            'remark'          => 'recharge',
            'sign'            => md5('merchantId=' . $merchantId . '&merchantOrderId=' . $pay_no . '&amount=' . $amount . '&abc#123!')
        ];
        $request    = $this->guzzle->create()->post(self::BASE_URL . 'rpay-api/order/submit', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data       = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') != 0) {
            throw new LogicException($data['error']);
        }

        return [
            'payUrl' => $data['data']['h5Url']
        ];
    }
}