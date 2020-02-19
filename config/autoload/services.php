<?php

return [
    [
        'consumers' => 'DemoService',
        'service'   => \App\JsonRpc\DemoServiceInterface::class,
        'id'        => \App\JsonRpc\DemoServiceInterface::class,
        'protocol'  => 'jsonrpc-http',
        'nodes'     => [
            ['host' => '127.0.0.1', 'port' => 9504],
        ],
    ],
];