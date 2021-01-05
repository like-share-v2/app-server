<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Controller\v1;

use App\Controller\AbstractController;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\View\RenderInterface;

/**
 * 注册控制器
 *
 * @AutoController()
 *
 * @package App\Controller\v1
 */
class ViewController extends AbstractController
{
    /**
     * h5注册页面
     *
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(RenderInterface $render)
    {
        return $render->render('register');
    }
}