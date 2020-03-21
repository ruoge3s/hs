<?php
declare(strict_types=1);

namespace App\Controller\Study;

use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;


/**
 * Class AopController
 * @Controller(prefix="/study")
 * @package App\Controller\Study
 */
class AopController extends AbstractController
{
    public function moduleName(): string
    {
        return 'study';
    }

    /**
     * @GetMapping(path="aop", methods="get")
     */
    public function demo()
    {
        return $this->message(200, [], 'success');
    }


}
