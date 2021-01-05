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
use App\Middleware\AuthMiddleware;
use App\Model\WithdrawalRule1;
use App\Service\Dao\UserDAO;
use App\Service\Dao\UserWithdrawalDAO;
use App\Service\UserWithdrawalService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户提现控制器
 *
 * @Controller()
 * @Middleware(AuthMiddleware::class)
 *
 * @package App\Controller\v1
 */
class UserWithdrawalController extends AbstractController
{
    /**
     * 用户提现
     *
     * @PostMapping(path="")
     */
    public function withdrawal()
    {
        $amount = (int)$this->request->input('amount', 0);

        $country_id = $this->request->input('country_id', '');

        $trade_pass = $this->request->input('trade_pass', '');

        // 提现倍数限制
        $withdrawalMultipleLimit = getConfig('withdrawalMultipleLimit', 0);
        if ($withdrawalMultipleLimit > 0 && $amount % $withdrawalMultipleLimit > 0) {
            $this->error('logic.WITHDRAWAL_MULTIPLE_LIMIT', 400, [
                'limit' => $withdrawalMultipleLimit
            ]);
        }

        $this->container->get(UserWithdrawalService::class)->withdrawal($amount, $country_id, $trade_pass);

        $this->success();
    }

    /**
     * 用户提现记录
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(UserWithdrawalDAO::class)->get($user_id);

        $this->success($result);
    }

    /**
     * 提现限制
     *
     * @GetMapping(path="limit")
     */
    public function getLimitConfig()
    {
        $low_limit = getConfig('withdrawal_low_limit', 0);

        $high_limit = getConfig('withdrawal_high_limit', 0);

        $day_count = getConfig('day_withdrawal_count', 0);

        // 每月提现免手续费次数
        $month_free_count = getConfig('month_withdrawal_free_count', 0);

        // 提现手续费
        $withdrawal_service_charge = getConfig('withdrawal_service_charge', 0) * 100 . '%';

        $this->success([
            'low_limit'                 => $low_limit,
            'high_limit'                => $high_limit,
            'day_count'                 => $day_count,
            'month_free_count'          => $month_free_count,
            'withdrawal_service_charge' => $withdrawal_service_charge
        ]);
    }

    /**
     * 获取提现银行列表
     *
     * @GetMapping(path="bank")
     */
    public function getBankList()
    {
        $bank_list = array_values(getConfig('withdrawal_bank', []));

        $this->success($bank_list);
    }

    /**
     * 提现规则1列表
     *
     * @GetMapping(path="rule1")
     */
    public function rule1()
    {
        $user_id  = JwtInstance::instance()->build()->getId();
        $ruleList = array_map(function ($item) {
            return [
                'name'            => $item['name'],
                'activeSubCount'  => $item['active_sub'],
                'withdrawalCount' => $item['withdrawal_count']
            ];
        }, WithdrawalRule1::query()->where('is_enable', 1)->get()->toArray());

        $activeSubCount         = $this->container->get(UserDAO::class)->getActiveSubCount($user_id);
        $currentWithdrawalCount = $this->container->get(UserWithdrawalDAO::class)->getUserWithdrawalCount($user_id);
        $withdrawalCount        = 0;
        /** @var WithdrawalRule1 $findRule */
        if ($findRule = WithdrawalRule1::query()
            ->where('active_sub', '<=', $activeSubCount)
            ->orderByDesc('withdrawal_count')
            ->where('is_enable', 1)
            ->first()
        ) {
            $withdrawalCount = $findRule->withdrawal_count - $currentWithdrawalCount;
        }
        $this->success([
            'ruleList'        => $ruleList,
            'withdrawalCount' => $withdrawalCount <= 0 ? 0 : $withdrawalCount,
            'subCount'        => $activeSubCount
        ]);
    }
}