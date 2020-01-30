<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Helper\HttpMessageBuilder;
use App\Logic\AccessToken;
use App\Model\User;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qingliu\Permission\Models\Permission;

/**
 * 管理后台-权限认证中间件
 * Class AdminAuthMiddleware
 * @package App\Middleware
 */
class AdminPermissionMiddleware implements MiddlewareInterface
{
    use HttpMessageBuilder;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dispatcher = $request->getAttribute(Dispatched::class);
        $path = strtolower($dispatcher->handler->route);
        $permission = Permission::getPermissions(['name' => $path])->first();
        /** @var User $user */
        $user = Context::get(AccessToken::TYPE_ADMIN);

        if ($user) {
            if (
                ($permission && $user->checkPermissionTo($permission)) // 普通权限
                || $user->isAdministrator() // 超级管理员权限
            ) {
                return $handler->handle($request);
            }
        }
        return $this->message(ErrorCode::PERMISSION_REFUSE);
    }
}
