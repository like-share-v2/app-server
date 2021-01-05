<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Listener;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * 监听器抽象类
 *
 *
 * @package App\Listener
 */
abstract class AbstractListener
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
}