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

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Memory\TableManager;
use Hyperf\Server\ServerFactory;
use Hyperf\Utils\ApplicationContext;
use Swoole\Table;

/**
 * Class HomeController
 * @Controller()
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    public function moduleName(): string
    {
        return 'home';
    }

    /**
     * @RequestMapping(path="/", methods="get,post")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return $this->response->raw(json_encode([
            'datetime'      => date('Y-m-d H:i:s'),
            'message'       => 'A project base on hyperf',
            'environment'   => config('app.env', 'local'), // 读取的是操作系统的环境变量,
            'php_version'   => PHP_VERSION,
            'swoole_version'=> swoole_version(),
            'version'       => config('app.version')
        ], JSON_PRETTY_PRINT));
    }
}
