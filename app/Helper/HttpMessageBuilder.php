<?php

declare(strict_types=1);

namespace App\Helper;

use App\Constants\ErrorCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HttpMessageBuilder
 * @uses 主要用于一些类无法调用控制器中的message方法，使用此方法代替
 * 和控制器中的消息构建方法保持一致
 * @package App\Helper
 */
trait HttpMessageBuilder
{
    public function message(int $code, array $data=null, string $message=null, ResponseInterface $response=null)
    {
        $info = [
            'code'      => $code,
            'message'   => $message ?? ErrorCode::getMessage($code),
        ];
        is_null($data) || $info['data'] = $data;
        $response = $response ?: Context::get(ResponseInterface::class);
        return $response
            ->withStatus(200)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream(Json::encode($info)));
    }
}
