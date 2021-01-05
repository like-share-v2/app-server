<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Service\Dao\BannerDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 轮播控制器
 *
 * @Controller()
 * @package App\Controller\v1
 */
class BannerController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->container->get(BannerDAO::class)->get();

        $this->success($result);
    }
}