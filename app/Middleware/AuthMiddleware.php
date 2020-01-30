<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Helper\HttpMessageBuilder;
use App\Logic\AccessToken;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 通用认证中间件
 * Class AdminAuthMiddleware
 * @package App\Middleware
 */
abstract class AuthMiddleware implements MiddlewareInterface
{
    use HttpMessageBuilder;

    protected $go = true;

    /**
     * 验证用户的类型，并把token中的载荷数据存入当前请求的协程上下文中，便于控制器处理
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = Arr::first($request->getHeader('Authorization'));
        if ($token && $payload = AccessToken::getPayload($token)) {
            if ($payload['type'] == $this->name()) {
                $this->handle($payload);
                if ($this->go) {
                    return $handler->handle($request);
                }
            }
        }
        return $this->message(ErrorCode::AUTH_FAILURE);
    }

    /**
     * 指定认证类型名称(accessToken的类型)
     * @return string
     */
    abstract protected function name(): string ;

    /**
     * 自定义的处理逻辑
     * @param $payload
     */
    abstract protected function handle($payload): void ;
}
