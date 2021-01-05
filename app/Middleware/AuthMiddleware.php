<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Constants;
use App\Exception\HttpException;
use App\Exception\LogicException;
use App\Exception\ResponseException;
use App\Kernel\Utils\JwtInstance;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dispatched = $request->getAttribute(\Hyperf\HttpServer\Router\Dispatched::class);
        if ($dispatched->handler === null) {
            throw new HttpException('logic.ROUTE_NOT_FOUND', 404);
        }

        // 获取Token
        $token = $request->getHeaderLine(Constants::AUTHORIZATION);
        if (empty($token)) {
            throw new ResponseException('logic.NEED_LOGIN', 401);
        }

        $user = JwtInstance::instance()->decode($token)->getUser();
        // 判断用户状态
        if (!$user || $user->status !== 1) {
            throw new ResponseException('logic.USER_STATUS_UNUSUAL', 401);
        }

        // 判断信用分
        if ($user->credit <= 150) {
            throw new ResponseException('logic.INSUFFICIENT_CREDIT_SCORE');
        }

        return $handler->handle($request);
    }
}