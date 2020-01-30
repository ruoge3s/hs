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

return [
    // 重写标准日志输出对象，重写日志到文件中
    \Hyperf\Contract\StdoutLoggerInterface::class => \App\Factory\StdoutLogger::class,
];
