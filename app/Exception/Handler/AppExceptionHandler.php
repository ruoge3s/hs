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
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 应用异常处理器,处理所有异常
 * Class AppExceptionHandler
 * @package App\Exception\Handler
 */
class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        if (config('app.env') == 'prod') {
            $header = ['Content-Type', 'application/json; charset=utf-8'];
            $content = 'Internal Server Error.';
        } else {
            $header = ['Content-Type', 'text/plain; charset=utf-8'];
            $content = json_encode([
                'code' => $throwable->getCode(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTrace()
            ]);
        }
        return $response
            ->withStatus(ErrorCode::SERVER_ERROR)
            ->withAddedHeader(...$header)
            ->withBody(new SwooleStream($content));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
