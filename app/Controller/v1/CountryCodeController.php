<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Service\Dao\CountryCodeDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 国家区号控制器
 *
 * @Controller()
 * @package App\Controller\v1
 */
class CountryCodeController extends AbstractController
{
    /**
     * 获取国家区号列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->container->get(CountryCodeDAO::class)->get();

        $this->success($result);
    }
}