<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Constants;
use App\Exception\HttpException;
use App\Exception\ResponseException;
use App\Kernel\Utils\JwtInstance;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AgentAuthMiddleware implements MiddlewareInterface
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
        if ($request->getUri()->getPath() === '/v1/agent/login') {
            return $handler->handle($request);
        }

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

        // 判断是否为代理
        if ($user->type !== 1) {
            throw new ResponseException('logic.USER_IS_NOT_AGENT');
        }

        return $handler->handle($request);
    }
}