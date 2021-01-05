<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;
use App\Service\UploadService;
use App\Middleware\AuthMiddleware;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 文件上传
 *
 * @Controller()
 * @Middleware(AuthMiddleware::class)
 *
 * @package App\Controller
 */
class UploadController extends AbstractController
{
    /**
     * @Inject()
     * @var UploadService
     */
    private $uploadService;

    /**
     * 单个文件上传
     *
     * @PostMapping(path="")
     */
    public function single()
    {
        if (!$file = $this->request->file('file')) {
            $this->error('logic.PLEASE_SELECT_FILE');
        }

        $result = $this->uploadService->handle($file, '');

        $this->success($result);
    }

    /**
     * 多个文件上传
     *
     * @PostMapping(path="multiple")
     */
    public function multiple()
    {
        $files = $this->request->file('files');
        $result = [];

        foreach ($files as $file) {
            $result[] = $this->uploadService->handle($file, '');
        }

        $this->success($result);
    }
}