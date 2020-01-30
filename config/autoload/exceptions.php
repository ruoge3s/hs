<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use App\Exception\Handler;

return [
    'handler' => [
        'http' => [
            // 定义异常处理
            Handler\ValidationExceptionHandler::class,
            Handler\InvalidArgumentExceptionHandler::class,
            Handler\AppExceptionHandler::class,
        ],
    ],
];
