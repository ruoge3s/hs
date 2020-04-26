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

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Helper\HttpMessageBuilder;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use \Hyperf\Validation\ValidationException;

/**
 * Class CustomExceptionHandler
 * 验证器验证失败handler
 * @package App\Exception\Handler
 */
class ValidationExceptionHandler extends ExceptionHandler
{
    use HttpMessageBuilder;

    /**
     * @param Throwable|ValidationException $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        return $this->message(ErrorCode::PARAMETER_ERROR, $throwable->validator->errors()->toArray(), null, $response);
    }

    /**
     * 仅处理ValidationException类型的异常
     * @param Throwable $throwable
     * @return bool
     */
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
