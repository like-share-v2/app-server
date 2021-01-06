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
 * YZPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class YZPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://yzpay.asia/';

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
        $params  = [
            'transactionId' => $pay_no,
            'amount'        => $amount,
            'payeeName'     => $extra['name'],
            'payeeEmail'    => $extra['email'],
            'payeePhone'    => $extra['phone'],
            'description'   => 'recharge',
            'payType'       => getConfig('YZPayType', 'eeziepay.bni'),
            'callback_url'  => config('app_host') . 'v1/notify/yz_pay'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/smart/deposit', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => [
                'secretKey'  => env('YZ_PAY_SECRET_KEY'),
                'productKey' => env('YZ_PAY_PRODUCT_KEY'),
                'encrypted'  => $this->enc($params, env('YZ_PAY_SECRET_KEY'), env('YZ_PAY_PRODUCT_KEY'))
            ]
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 1) {
            throw new LogicException($data['message']);
        }

        return [
            'payUrl' => $data['url']
        ];
    }

    /**
     * 加密数据
     *
     * @param $d
     * @param $sk
     * @param $pk
     *
     * @return string
     */
    public function enc($d, $sk, $pk)
    {
        $plaintext      = base64_encode(json_encode($d));
        $cipher         = "AES-128-CBC";
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $sk, $options = OPENSSL_RAW_DATA, $pk);
        $hmac           = hash_hmac('sha256', $ciphertext_raw, $sk, $as_binary = true);
        $ciphertext     = base64_encode($pk . $hmac . $ciphertext_raw);
        return $ciphertext;
    }
}