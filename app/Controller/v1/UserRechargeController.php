<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Request\UserRecharge\BankRequest;
use App\Request\UserRecharge\LevelRequest;
use App\Request\UserRecharge\ManualRequest;
use App\Service\Dao\RechargeQrCodeDAO;
use App\Service\Dao\UserBankRechargeDAO;
use App\Service\Dao\UserManualRechargeDAO;
use App\Service\Dao\UserRechargeDAO;
use App\Service\UserRechargeService;
use App\Middleware\AuthMiddleware;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 充值控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller\v1
 */
class UserRechargeController extends AbstractController
{
    /**
     * 手动扫码充值
     *
     * @PostMapping(path="manual")
     * @param ManualRequest $request
     */
    public function manual(ManualRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserRechargeService::class)->manual($params);

        $this->success();
    }

    /**
     * 获取充值记录
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $type = $this->request->input('type', 1);

        $user_id = JwtInstance::instance()->build()->getId();

        switch ($type) {
            case 1:
                $result = $this->container->get(UserRechargeDAO::class)->getListByUserId($user_id);
                break;
            case 2:
                $result = $this->container->get(UserManualRechargeDAO::class)->getListByUserId($user_id);
                break;
            case 3:
                $result = $this->container->get(UserBankRechargeDAO::class)->getListByUserId($user_id);
                break;
            default:
                $result = [];
                $this->error('logic.SERVER_ERROR');
                break;
        }

        $this->success($result);
    }

    /**
     * 随机获取扫码充值二维码
     *
     * @GetMapping(path="qr_code")
     */
    public function getManualQrCode()
    {
        $result = $this->container->get(RechargeQrCodeDAO::class)->getQrCodeImage();

        $this->success($result);
    }

    /**
     * 获取收款银行信息
     *
     * @GetMapping(path="receiving_bank")
     */
    public function getReceivingBankInfo()
    {
        $receiving_bank = getConfig('receiving_bank', '');

        $payee = getConfig('payee', '');

        $receiving_bank_card = getConfig('receiving_bank_card', '');

        $this->success([
            'receiving_bank'      => $receiving_bank,
            'payee'               => $payee,
            'receiving_bank_card' => $receiving_bank_card
        ]);
    }

    /**
     * 银行卡充值接口
     *
     * @PostMapping(path="bank_recharge")
     * @param BankRequest $request
     */
    public function bankRecharge(BankRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserRechargeService::class)->bankRecharge($params);

        $this->success();
    }

    /**
     * 充值会员等级
     *
     * @PostMapping(path="level")
     * @param LevelRequest $request
     */
    public function levelRecharge(LevelRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserRechargeService::class)->levelRecharge($params);

        $this->success();
    }

    /**
     * 获取在线充值渠道状态
     *
     * @GetMapping(path="online_recharge_status")
     */
    public function getOnlineRechargeStatus()
    {
        $status = (int)getConfig('online_recharge_status', 0);

        $this->success($status);
    }

    /**
     * @PostMapping(path="onlineRecharge")
     */
    public function onlineRecharge()
    {
        $amount     = (int)$this->request->input('amount', 0);
        $country_id = (int)$this->request->input('country_id', 0);
        $channel    = $this->request->input('channel', getConfig('pay_channel', ''));
        $pay_code   = $this->request->input('pay_code', '');

        // 最低充值金额
        $min_amount = getConfig('MinRechargeAmount', 0);
        if ($min_amount > 0 && $amount < $min_amount) {
            $this->error('logic.MIN_RECHARGE', 400, [
                'amount' => $min_amount
            ]);
        }

        $result = $this->container->get(UserRechargeService::class)->pay($channel, $amount, $country_id, [
            'pay_code' => $pay_code
        ]);

        $this->success([
            'pay_url' => $result['payUrl']
        ]);
    }
}