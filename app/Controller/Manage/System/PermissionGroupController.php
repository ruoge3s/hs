<?php
declare(strict_types=1);

namespace App\Controller\Manage\System;

use App\Constants\ErrorCode;
use App\Controller\Manage\CommonController;
use App\Model\PermissionGroup;
use App\Model\PermissionHasGroup;
use App\Request\PermissionGroupCreateRequest;
use App\Request\PermissionGroupUpdateRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PermissionGroupController
 * 接口权限分组管理
 * @package App\Controller\Manage\System
 */
class PermissionGroupController extends CommonController
{
    /**
     * @param PermissionGroupCreateRequest $request
     * @return ResponseInterface
     */
    public function create(PermissionGroupCreateRequest $request)
    {
        $request->validateResolved();

        $pg = new PermissionGroup;
        if ($pg->fill($request->validated())->save()) {
            return $this->success($pg->toArray());
        }
        return $this->failure(ErrorCode::OPERATE_FAILURE, '创建失败');
    }

    /**
     * 更新分组
     * @param PermissionGroupUpdateRequest $request
     * @return ResponseInterface
     */
    public function update(PermissionGroupUpdateRequest $request)
    {
        $request->validateResolved();
        $data = $request->validated();
        /** @var PermissionGroup $pg */
        $pg = PermissionGroup::query()->find($data['id']);
        if ($pg) {
            $pg->name = $data['name'];
            $pg->sort = $data['sort'];
            return $pg->save()
                ? $this->success($pg->toArray())
                : $this->failure(ErrorCode::OPERATE_FAILURE);
        } else {
            return $this->failure(ErrorCode::NOT_FOUND);
        }
    }

    /**
     * @return ResponseInterface
     */
    public function lists()
    {
        $name   = $this->request->query('name');
        $id     = (int)$this->request->query('id');

        $label = PermissionGroup::query()->orderBy('sort', 'desc');
        $name && $label->where('name', 'like', "%{$name}%");
        $id && $label->where('id', '=', $id);

        $paginate = $label->paginate($this->limit());
        return $this->success($this->paginate($paginate));
    }

    /**
     * 删除
     * @param int $id
     * @return ResponseInterface
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $pg = PermissionGroup::query()->find($id);
        if ($pg) {
            if ($pg->delete()) {
                // 尝试删除相关的标签
                PermissionHasGroup::query()
                    ->where('gid', '=', $pg->id)
                    ->delete();
                return $this->success();
            } else {
                return $this->failure(ErrorCode::OPERATE_FAILURE);
            }
        } else {
            return $this->failure(ErrorCode::NOT_FOUND);
        }
    }

    /**
     * 获取权限组map
     * @return ResponseInterface
     */
    public function map()
    {
        $name   = $this->request->query('name');
        $pg = PermissionGroup::query();
        $name && $pg->where('name', 'like', "%{$name}%");
        $map = $pg
            ->pluck('name', 'id')
            ->toArray();
        return $this->success($map);
    }

}
