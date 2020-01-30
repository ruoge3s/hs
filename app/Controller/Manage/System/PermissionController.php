<?php

declare(strict_types=1);

namespace App\Controller\Manage\System;

use App\Constants\ErrorCode;
use App\Controller\Manage\CommonController;
use App\Model\PermissionHasGroup;
use App\Model\User;
use App\Request\PermissionCreateRequest;
use App\Request\PermissionUpdateRequest;
use Hyperf\Utils\Arr;
use Qingliu\Permission\Models\Permission;
use Qingliu\Permission\Models\Role;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 * 权限管理
 * @package App\Controller\Manage
 */
class PermissionController extends CommonController
{
    /**
     * 创建权限
     * @param PermissionCreateRequest $request
     * @return ResponseInterface
     */
    public function create(PermissionCreateRequest $request)
    {
        $request->validateResolved();

        $role = Permission::create(Arr::only($request->validated(), ['name', 'describe']));

        $gid = Arr::get($request->validated(), 'gid', null);
        $gid && PermissionHasGroup::relate($role->id, (int)$gid);

        return $this->success($role->toArray());
    }

    /**
     * 获取权限列表
     * @return ResponseInterface
     */
    public function lists()
    {
        $permission = Permission::query()->orderBy('id', 'desc');
        $paginate = $permission
            ->leftJoin('permission_has_group as phg', 'phg.id', '=', 'permissions.id')
            ->paginate($this->limit(), ['permissions.*', 'phg.gid']);
        return $this->success($this->paginate($paginate));
    }

    public function map()
    {
        $name = $this->request->query('name');
        $gname = $this->request->query('gname');
        $permission = Permission::query()
            ->orderBy('id', 'desc')
            ->leftJoin('permission_has_group as phg', 'phg.id', '=', 'permissions.id');

        $name && $permission->where('permissions.name', '=', $name);
        $gname && $permission->where('phg.name', '=', $gname);

        $items = $permission->get([
            'permissions.id',
            'permissions.name',
            'permissions.describe',
            'phg.gid'
        ]);
        $groups = $items->groupBy('gid')->toArray();

        $data = [];
        foreach ($groups as $key => $group) {
            $data[] = [
                'id' => $key ?: 0,
                'children' => $group
            ];
        }
        return $this->success($data);
    }

    /**
     * 更新权限
     * @param PermissionUpdateRequest $request
     * @return ResponseInterface
     */
    public function update(PermissionUpdateRequest $request)
    {
        $request->validateResolved();
        $params = $request->validated();
        if ($role = Permission::query()->find($params['id'])) {
            isset($params['name']) && $role->name = $params['name'];
            isset($params['describe']) && $role->describe = $params['describe'];

            // TODO 减少复杂度，不使用事务 后期迭代严谨化时，使用事务
            PermissionHasGroup::relate((int)$params['id'], (int)$params['gid']);

            return $role->save() ? $this->success($role->toArray()) : $this->failure(ErrorCode::OPERATE_FAILURE, '保存失败');
        }
        return $this->failure(ErrorCode::NOT_FOUND);
    }

    /**
     * 删除一个权限
     * @param int $id
     * @return ResponseInterface
     * @throws \Exception
     */
    public function delete(int $id)
    {
        /** @var Permission $permission */
        $permission = Permission::findById($id);
        if ($permission) {
            if ($permission->delete()) {

                // TODO 删除相关的无用数据
                PermissionHasGroup::query()->where('id', '=', $permission->id)->delete();

                return $this->success();
            } else {
                return $this->message(ErrorCode::OPERATE_FAILURE);
            }
        }
        return $this->message(ErrorCode::NOT_FOUND);
    }

    /**
     * 权限关联给角色
     * @return ResponseInterface
     */
    public function permissionWithRoles()
    {
        return $this->permissionRelateRole(true);
    }

    /**
     * 权限和角色分离
     * @return ResponseInterface
     */
    public function permissionSeparateRole()
    {
        return $this->permissionRelateRole(false);
    }

    /**
     * 权限与角色的关系
     * @param bool $love
     * @return ResponseInterface
     */
    protected function permissionRelateRole(bool $love=true)
    {
        $rid = (int)$this->request->post('rid');
        $pid = (int)$this->request->post('pid');
        $role = Role::findById($rid);
        $permission = Permission::findById($pid);
        if ($love) {
            $permission->assignRole($role);
            return $this->success($permission->toArray());
        } else {
            $permission->removeRole($role);
            return $this->success();
        }
    }

    /**
     * 用户关联权限
     * @return ResponseInterface
     */
    public function userWithPermissions()
    {
        $uid = (int)$this->request->post('uid');
        $pid = (int)$this->request->post('pid');

        $permission = Permission::findById($pid);
        $user = User::query()->find($uid);
        $user->givePermissionTo($permission);

        return $this->success($user->toArray());
    }
}
