<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Memory\TableManager;
use Hyperf\Server\ServerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server;

/**
 * websocket服务
 * Class WebSocketController
 * @package App\Controller
 */
class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, $frame->fd . '-rcv-' . $frame->data);
        // TODO: Implement onMessage() method.
    }

    public function onOpen(Server $server, Request $request): void
    {
        $server->push($request->fd, 'open');
    }

    public function onClose(\Swoole\Server $server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.
        ApplicationContext::getContainer()
            ->get(LoggerFactory::class)
            ->get('app')
            ->info($fd . ' close');
    }

    // TODO 该方法写在控制器中，逻辑，服务，队列任务等功能区，用于服务器主动向客户端推送消息
    private function test()
    {
        // 获取websocket对象
        // TODO 如果启动多个websocket， 如何知道是获取的那个websocket服务对象呢
        $wsServer = ApplicationContext::getContainer()->get(ServerFactory::class)->getServer()->getServer();

        // 获取当前要推送的fd
        $fd = 1;
        $msg = '测试';

        if ($wsServer->exists($fd)) {
            $message = $wsServer->push((int)$fd, $msg) ? 'ok' : 'ng';
        } else {
            $message = '不存在';
        }

        echo $message;
    }
}
