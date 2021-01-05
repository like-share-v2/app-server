<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Service\Dao\UserBillDAO;
use App\Middleware\AuthMiddleware;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * 用户账单控制器
 *
 * @Middleware(AuthMiddleware::class)
 * @Controller()
 *
 * @package App\Controller
 */
class UserBillController extends AbstractController
{
    /**
     * 获取用户账单列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $type = (int)$this->request->input('type', 1);

        $result = $this->container->get(UserBillDAO::class)->getListByUserId($user_id, $type);

        $this->success($result);
    }

    /**
     * 获取用户今日收益金额
     *
     * @GetMapping(path="today_profit")
     */
    public function getTodayProfit()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $result = $this->container->get(UserBillDAO::class)->getTodayProfitByUserId($user_id);

        $this->success($result);
    }
}