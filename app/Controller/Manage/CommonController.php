<?php

declare(strict_types=1);

namespace App\Controller\Manage;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Logic\AccessToken;
use App\Model\Model;
use App\Model\User;
use Hyperf\Utils\Context;
use \Psr\Http\Message\ResponseInterface;

/**
 * 管理后台相关接口
 * Class CommonController
 * @package App\Controller\Manage
 */
class CommonController extends AbstractController
{
    public function moduleName(): string
    {
        return 'manage';
    }

    /**
     * 获取当前登录的管理员
     * @return User
     */
    public function admin(): User
    {
        return Context::get(AccessToken::TYPE_ADMIN);
    }

    /**
     * 通用创建数据保存功能
     * @param \Hyperf\Database\Model\Model|\Hyperf\Database\Model\Collection|Model $model
     * @param array $data
     * @return ResponseInterface
     */
    protected function save(Model $model, array $data)
    {
        if ($model->fill($data)->save()) {
            return $this->success($model->toArray());
        } else {
            return $this->message(ErrorCode::OPERATE_FAILURE);
        }
    }
}
