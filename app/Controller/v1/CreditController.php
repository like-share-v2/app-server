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
use App\Middleware\AuthMiddleware;
use App\Service\CreditService;
use App\Service\Dao\AgreementDAO;
use App\Service\Dao\UserCreditRecordDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户信用控制器
 *
 * @Controller()
 * @Middleware(AuthMiddleware::class)
 *
 * @package App\Controller\v1
 */
class CreditController extends AbstractController
{
    /**
     * 查看个人信用记录
     *
     * @GetMapping(path="")
     */
    public function getRecordList()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $type = (int)$this->request->input('type', 1);

        $result = $this->container->get(UserCreditRecordDAO::class)->get($user_id, $type);

        $this->success($result);
    }

    /**
     * 用户签到操作
     *
     * @PostMapping(path="sign_in")
     */
    public function signIn()
    {
        $user_id = JwtInstance::instance()->build()->getId();

        $this->container->get(CreditService::class)->signIn($user_id);

        $this->success();
    }

    /**
     * 获取信用分规则
     *
     * @GetMapping(path="rule")
     */
    public function getCreditRule()
    {
//        $content = getConfig('credit_rule', '');

        $content = $this->container->get(AgreementDAO::class)->get(['type' => 3]);

        $this->success($content);
    }
}