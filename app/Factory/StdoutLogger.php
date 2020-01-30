<?php
declare(strict_types=1);

namespace App\Factory;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * 重新定义标准输出工厂，写日志到日志文件中
 * Class StdoutLogger
 * @package App
 */
class StdoutLogger
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        return ApplicationContext::getContainer()
            ->get(LoggerFactory::class)
            ->get('sys');
    }
}
