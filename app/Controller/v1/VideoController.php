<?php

declare(strict_types=1);

namespace App\Controller\v1;

use App\Controller\AbstractController;

use App\Service\Dao\VideoDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 视频控制器
 *
 * @Controller()
 * @package App\Controller\v1
 */
class VideoController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->container->get(VideoDAO::class)->get();
        $this->success($result);
    }
}