<?php
declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Annotation;

/**
 * Http重试注解
 *
 * @Annotation
 * @Target({"METHOD"})
 *
 * @package App\Annotation
 */
class HttpRetry extends \Hyperf\Retry\Annotation\AbstractRetry
{
    /**
     * 重试策略
     *
     * @var array
     */
    public $policies = [
        \Hyperf\Retry\Policy\MaxAttemptsRetryPolicy::class, // 最大尝试次数策略
        \Hyperf\Retry\Policy\ClassifierRetryPolicy::class, // 错误分类策略
    ];

    /**
     * 最大重试次数
     *
     * @var int
     */
    public $maxAttempts = 3;

    /**
     * 异常类列表
     *
     * @var array
     */
    public $retryThrowables = [
        \App\Exception\HttpException::class
    ];
}