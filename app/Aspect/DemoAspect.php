<?php
declare(strict_types=1);

namespace App\Aspect;

use App\Controller\Study\AopController;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * Class DemoAspect
 * @Aspect()
 * @package app\Aspect
 */
class DemoAspect extends AbstractAspect
{
    /**
     * @var array 被切入的类
     */
    public $classes = [
//        AopController::class
    ];

    /**
     * @var array 被切入的注解
     */
    public $annotations = [];

    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws \Hyperf\Di\Exception\Exception
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {

        // TODO 在处理前进行操作
        $result = $proceedingJoinPoint->process();
        // TODO 在处理后进行操作

        var_dump($result);

        return $result;
    }
}

