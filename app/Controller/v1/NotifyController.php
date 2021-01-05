<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Payment\JasonBagPay;
use App\Service\NotifyService;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Utils\Codec\Json;

/**
 *
 * @AutoController()
 * @package App\Controller\v1
 */
class NotifyController extends AbstractController
{
    public function gaga_pay()
    {
        $params = $this->request->all();
        if (!isset($params['status']) || !isset($params['outTradeNo'])) {
            return 'fail';
        }

        $this->logger('gaga_pay')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 1: // 下单成功
                    $status = 1;
                    break;

                case 3: // 支付成功
                    $status = 2;
                    break;

                case 7: // 已退款
                    $status = -1;
                    break;

                default: // 支付失败
                    $status = 3;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['outTradeNo'], $status, '', $params['message'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('gaga_pay')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function gaga_payout()
    {
        $params = $this->request->all();
        if (!isset($params['status']) || !isset($params['outTradeNo'])) {
            return 'fail';
        }

        $this->logger('gaga_payout')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 1: // 下单成功
                    $status = 0;
                    break;

                case 2: // 支付中
                    $status = 0;
                    break;

                case 3: // 支付成功
                    $status = 1;
                    break;

                default: // 支付失败
                    $status = 2;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['outTradeNo'], $status, (float)$params['amount'], $params['message'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('gaga_payout')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function ipay()
    {
        $params = $this->request->all();
        if (!isset($params['pay_result']) || !isset($params['out_trade_no'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['pay_result']) {
                case 'success': // 支付成功
                    $status = 2;
                    break;

                case 'fail': // 支付失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['out_trade_no'], $status, $params['pltf_order_id'] ?? '', $params['message'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function link_pay()
    {
        $params = $this->request->all();
        if (!isset($params['result']) || !isset($params['spbillno'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['result']) {
                case 'pay_unpaid':
                case 'pay_processing':
                    $status = 1;
                    break;

                case 'pay_success': // 支付成功
                    $status = 2;
                    break;

                default:
                    $status = 3;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['spbillno'], $status, $params['transactionId'] ?? '', $params['retmsg'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function yt_pay()
    {
        $params = $this->request->all();
        if (!isset($params['returncode']) || !isset($params['terraceordercode'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['returncode']) {
                case '00': // 支付成功
                    $status = 2;
                    break;

                default:
                    $status = 3;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['terraceordercode'], $status, $params['merordercode'] ?? '', 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'OK';
    }

    public function dsed_pay()
    {
        $params = $this->request->all();
        if (!isset($params['callbacks']) || !isset($params['out_trade_no'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['callbacks']) {
                case 'CODE_SUCCESS': // 支付成功
                    $status = 2;
                    break;

                default:
                    $status = 3;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['out_trade_no'], $status, '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function link_payout()
    {
        $params = $this->request->all();
        if (!isset($params['result']) || !isset($params['spbillno'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['result']) {
                case 'processsuccess': // 支付成功
                    $status = 2;
                    break;

                case 'processfailed': // 支付失败
                case 'processreject':
                    $status = 1;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['spbillno'], $status, (float)$params['tranAmt'], $params['msg'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function ipay_payout()
    {
        $params = $this->request->all();
        if (!isset($params['out_trade_no']) || !isset($params['rtn_code'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['rtn_code']) {
                case 'fail': // 支付失败
                    $status = 1;
                    break;

                case 'success': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['out_trade_no'], $status, (float)$params['money'], $params['msg'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function dsed_payout()
    {
        $params = $this->request->all();
        if (!isset($params['code']) || !isset($params['out_trade_no'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['code']) {
                case '0': // 支付失败
                    $status = 1;
                    break;

                case '1': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['out_trade_no'], $status, (float)$params['money'], $params['msg'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function yt_payout()
    {
        $params = $this->request->all();
        if (!isset($params['returncode']) || !isset($params['merissuingcode'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['returncode']) {
                case 'FAIL': // 支付失败
                    $status = 1;
                    break;

                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['merissuingcode'], $status, (float)$params['amount'], $params['issuingcode'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'OK';
    }

    public function step_pay()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['outTradeNo']) || !isset($params['status'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 'PAY_FAILED': // 支付失败
                    $status = 1;
                    break;

                case 'SETTLED': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['outTradeNo'], $status, $params['tradeNo'] ?? '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'SUCCESS';
    }

    public function ipay_india()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['orderNo']) || !isset($params['payStatus'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ((int)$params['payStatus']) {
                case 1: // 支付成功
                    $status = 2;
                    break;

                default: // 支失败
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['orderNo'], $status, '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function ipay_india_payout()
    {
        $params = $this->request->all();
        if (!isset($params['orderNo']) || !isset($params['status'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 3: // 支付失败
                    $status = 1;
                    break;

                case 2: // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['orderNo'], $status, 0, $params['desc'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function hzpay()
    {
        $params = $this->request->all();
        if (!isset($params['mer_order_no']) || !isset($params['status'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                case 'FAIL': // 支付失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['mer_order_no'], $status, $params['order_no'] ?? '', $params['err_msg'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'SUCCESS';
    }

    public function haoda_pay()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['orderId']) || !isset($params['status'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                case 'FAIL': // 失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['orderId'], $status, $params['tradeId'] ?? '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'SUCCESS';
    }

    public function popmodepay()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['out_trade_no']) || !isset($params['trade_state'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['trade_state']) {
                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                case 'NOTPAY': // 失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['out_trade_no'], $status, $params['pay_orderid'] ?? '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'ok';
    }

    public function popmodepayout()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['out_trade_no']) || !isset($params['result_code'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['result_code']) {
                case 'FAIL': // 支付失败
                    $status = 1;
                    break;

                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['out_trade_no'], $status);
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'ok';
    }

    public function yt2pay()
    {
        $params = $this->request->all();
        if (!isset($params['returncode']) || !isset($params['terraceordercode'])) {
            return 'fail';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['returncode']) {
                case '00': // 支付成功
                    $status = 2;
                    break;

                default:
                    $status = 3;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['terraceordercode'], $status, $params['merordercode'] ?? '', 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'OK';
    }

    public function yt2payout()
    {
        $params = $this->request->all();
        if (!isset($params['returncode']) || !isset($params['merissuingcode'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['returncode']) {
                case 'FAIL': // 支付失败
                    $status = 1;
                    break;

                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['merissuingcode'], $status, (float)$params['amount'], $params['issuingcode'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'OK';
    }

    public function shineupay()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['orderId']) || !isset($params['status'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ((int)$params['status']) {
                case 1: // 支付成功
                    $status = 2;
                    break;

                case 2: // 支付失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['orderId'], $status, $params['platformOrderId'] ?? '', $params['message'] ?? 'success');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function shineupayout()
    {
        $params = Json::decode($this->request->getBody()->getContents(), true);
        if (!isset($params['orderId']) || !isset($params['status'])) {
            return 'fail';
        }
        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 2: // 支付失败
                    $status = 1;
                    break;

                case 1: // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['orderId'], $status, (float)$params['Amount']);
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'success';
    }

    public function jason_bag_pay()
    {
        $params = $this->request->all();
        if (!isset($params['orderNo']) || !isset($params['status'])) {
            return 'fail';
        }
        if ($this->container->get(JasonBagPay::class)->getSign($params, env('JASON_BAG_PAY_KEY')) !== $params['sign']) {
            return 'sign error';
        }

        $this->logger('payment')->info(Json::encode($params));

        try {
            switch ($params['status']) {
                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                case 'FAILUE': // 支付失败
                    $status = 3;
                    break;

                default:
                    $status = 1;
                    break;
            }
            $this->container->get(NotifyService::class)->handle($params['orderNo'], $status, $params['tradeNo'] ?? '');
        }
        catch (\Exception $e) {
            $this->logger('payment')->info($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'SUCCESS';
    }

    public function jason_bag_payout()
    {
        $params = $this->request->all();
        if (!isset($params['orderNo']) || !isset($params['status'])) {
            return 'fail';
        }
        try {
            switch ($params['status']) {
                case 'FAILUE': // 支付失败
                    $status = 1;
                    break;

                case 'SUCCESS': // 支付成功
                    $status = 2;
                    break;

                default: // 支付中
                    $status = 0;
                    break;
            }
            $this->container->get(NotifyService::class)->handlePayout($params['orderNo'], $status, (float)$params['amount']);
        }
        catch (\Exception $e) {
            $this->logger('payment')->error($e->getMessage(), $params);
            return $e->getMessage();
        }

        return 'SUCCESS';
    }
}