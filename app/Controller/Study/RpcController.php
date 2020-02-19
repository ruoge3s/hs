<?php
declare(strict_types=1);

namespace App\Controller\Study;

use App\Controller\AbstractController;
use App\JsonRpc\DemoServiceInterface;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;


/**
 * Class RpcController
 * @Controller(prefix="/study")
 * @package App\Controller\Study
 */
class RpcController extends AbstractController
{

    /**
     * @var DemoServiceInterface
     */
    protected $demoService;

    public function moduleName(): string
    {
        return 'study';
    }

    /**
     * @GetMapping(path="json/rpc", methods="get")
     */
    public function demo()
    {
        $data = $this->demoService->lists(1, 'ql', 'test');
        return $this->message(200, $data, 'success');
    }


}
