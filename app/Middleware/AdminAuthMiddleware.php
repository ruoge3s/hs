<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Logic\AccessToken;
use App\Model\User;
use Hyperf\Utils\Context;


/**
 * 管理后台-Token认证中间件
 * Class AdminMiddleware
 * @package App\Middleware
 */
class AdminAuthMiddleware extends AuthMiddleware
{
    protected function handle($payload): void
    {
        $user = User::findFromCache($payload['id'] ?? 0);
        if ($user) {
            Context::set($this->name(), $user);
        } else {
            $this->go = false;
        }
    }

    protected function name(): string
    {
        return AccessToken::TYPE_ADMIN;
    }
}

