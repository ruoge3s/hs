<?php

namespace App\JsonRpc;


/**
 * Class DemoService
 * @RpcService(name="DemoService", protocol="jsonrpc-http", server="jsonrpc-http-test")
 * @package App\JsonRpc
 */
interface DemoServiceInterface
{
    public function lists(int $id, string $name, string $channel): array;
}