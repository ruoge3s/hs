<?php
declare(strict_types=1);

namespace App\JsonRpc;

use Hyperf\RpcServer\Annotation\RpcService;
use Silk\Contract\DemoServiceInterface;

/**
 * Class DemoService
 * @RpcService(name="DemoService", protocol="jsonrpc", server="jsonrpc-test")
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
                ['name', 'sex', 'age', 'remark', date('Y-m-d H:i:s')],
                ['name', 'sex', 'age', 'remark', date('Y-m-d H:i:s')],
            ]
        ];
    }
}