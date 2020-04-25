<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

/**
 * 测试命令
 * @Command
 */
class Test extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('try');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('测试命令');
    }

    public function handle()
    {
        $message = '环境:' . config('app.env', 'local');
        // TODO 测试相关的代码，测试完毕后删除
        $this->line($message, 'info');
    }
}
