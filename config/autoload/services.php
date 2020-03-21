<?php

use Silk\Contract\DemoServiceInterface;

return [
    'consumers' => [
        [
            'name'      => 'DemoService',
            'service'   => DemoServiceInterface::class,
            'id'        => DemoServiceInterface::class,
            'protocol'  => 'jsonrpc',
            'nodes'     => [
                [
                    'host' => '127.0.0.1',
                    'port' => 9504
                ],
            ],
            'options' => [
                'connect_timeout'   => 5.0,
                'recv_timeout'      => 5.0,
                'settings'          => [
                    'open_eof_split'        => true,
                    'package_eof'           => "\r\n",
                ],
                // 当使用 JsonRpcPoolTransporter 时会用到以下配置
                'pool' => [
                    'min_connections'   => 1,
                    'max_connections'   => 32,
                    'connect_timeout'   => 10.0,
                    'wait_timeout'      => 3.0,
                    'heartbeat'         => -1,
                    'max_idle_time'     => 60.0,
                ],
            ],
        ],
    ]
];