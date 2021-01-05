<?php

declare(strict_types=1);

namespace App\Kernel\Payment;

use App\Common\Base;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;

/**
 * @package App\Kernel\Payment
 */
class EfuPay extends Base
{
    /**
     * @Inject()
     * @var ClientFactory
     */
    private $guzzle;

    public $url = 'http://efupays.com:8084/';

    public $merchantNo = 'API15885610344163100';

    public $secretKey = '36c67ddd576f462bb6df97467cb80232';

    public function pay(float $yuan, string $date, string $order_no)
    {
        var_dump(env('HOST') . 'notify/efuPayNotify');

        $data = [
            'version' => 'V2',
            'signType' => 'MD5',
            'merchantNo' => $this->merchantNo,
            'date' => $date,
            'channleType' => 0,
            'noticeUrl' => env('HOST') . 'notify/efuPayNotify',
            'orderNo' => $order_no,
            'bizAmt' => $yuan
        ];

        $sign = $this->getMd5Sign($data);

        $data['sign'] = $sign;

        $guzzle = $this->guzzle->create();
        try {
            $response = $guzzle->post($this->url . 'api/pay/V2', [
                'json' => $data
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new \Exception(sprintf('Response status code is abnormal: %s', $response->getStatusCode()));
            }
            $responseContents = $response->getBody()->getContents();
            $result           = Json::decode($responseContents, true);

            if ($result['code'] === '-1' || !isset($result['code'])) {
                $this->logger('efuPay')->error(json_encode($result));
                throw new \Exception(__('logic.SERVER_ERROR'));
            }

            return $result['detail']['PayURL'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    public function getMd5Sign(array $data)
    {
        ksort($data);

        $sign = urldecode(http_build_query($data) . $this->secretKey);

        return md5($sign);
    }
}