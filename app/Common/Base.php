<?php

declare (strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link
 */

namespace App\Common;

use App\Exception\ResponseException;

use GuzzleHttp\Client;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Cache\Cache;
use Hyperf\Cache\Listener\DeleteListenerEvent;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Context;
use League\Flysystem\Filesystem;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * 基类
 *
 *
 * @package App\Common
 */
abstract class Base
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @Inject()
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    /**
     * @Inject()
     * @var IdGeneratorInterface
     */
    protected $snowflake;

    /**
     * 错误响应
     *
     * @param string $message
     * @param int    $code
     * @param array  $placeholder
     */
    protected function error(string $message, int $code = 400, array $placeholder = [])
    {
        Context::set('replace', $placeholder);

        throw new ResponseException($message, $code);
    }

    /**
     * 针对表单的错误响应
     *
     * @throws ResponseException
     * @param array $errors
     */
    protected function formError(array $errors = [])
    {
        Context::set('errors', $errors);

        throw new ResponseException('', 400);
    }

    /**
     * 成功响应
     *
     * @throws ResponseException
     * @param mixed $data
     */
    protected function success($data = [])
    {
        Context::set('successful_data', $data);

        throw new ResponseException('success', 200);
    }

    /**
     * 清理缓存
     *
     * @param string $listener
     * @param array $args
     */
    protected function flushCache(string $listener, array $args)
    {
        $this->eventDispatcher->dispatch(new DeleteListenerEvent($listener, $args));
    }

    /**
     * 文件上传
     *
     * @param string $driver
     * @return Filesystem
     */
    protected function upload(string $driver = 'cos'): Filesystem
    {
        return $this->container->get(\Hyperf\Filesystem\FilesystemFactory::class)->get($driver);
    }

    /**
     * 日志管理
     *
     * @param string $name
     * @param string $channel
     * @return LoggerInterface
     */
    protected function logger(string $channel, string $name = 'log'): LoggerInterface
    {
        return $this->container->get(LoggerFactory::class)->get($name, $channel);
    }

    /**
     * 消息队列投递
     *
     * @param string $channel
     * @return DriverInterface
     */
    protected function asyncQueue(string $channel = 'default'): DriverInterface
    {
        return $this->container->get(\Hyperf\AsyncQueue\Driver\DriverFactory::class)->get($channel);
    }

    /**
     * Guzzle
     *
     * @return Client
     */
    protected function guzzle(): Client
    {
        return $this->container->get(\Hyperf\Guzzle\ClientFactory::class)->create();
    }

    /**
     * Cache
     *
     * @return Cache|mixed
     */
    protected function cache()
    {
        return $this->container->get(Cache::class);
    }
}