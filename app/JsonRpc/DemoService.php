<?php
declare(strict_types=1);

namespace App\JsonRpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class DemoService
 * @RpcService(name="DemoService", protocol="jsonrpc-http", server="jsonrpc-http-test")
 * @package App\JsonRpc
 */
class DemoService implements DemoServiceInterface
{
    public function lists(int $id, string $name, string $channel): array
    {
        return [
            'query' => [
                'id'        => $id,
                'name'      => $name,
                'channel'   => $channel,
            ],
            'data' => [
                ['name', 'sex', 'age', 'remark'],
                ['name', 'sex', 'age', 'remark'],
                ['name', 'sex', 'age', 'remark'],
                ['name', 'sex', 'age', 'remark'],
            ]
        ];
    }
}