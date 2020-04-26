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
use App\Constants\ModelName;
use App\Helper\HttpMessageBuilder;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Class CustomExceptionHandler
 * model数据未找到异常handler
 * @package App\Exception\Handler
 */
class ModelNotFoundExceptionHandler extends ExceptionHandler
{
    use HttpMessageBuilder;

    /**
     * @param Throwable|ModelNotFoundException $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        $modelName = ModelName::getMessage($throwable->getModel());
        return $this->message(ErrorCode::NOT_FOUND, null, "找不到[{$modelName}]", $response);
    }

    /**
     * 仅处理ModelNotFoundException类型的异常
     * @param Throwable $throwable
     * @return bool
     */
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ModelNotFoundException;
    }
}
