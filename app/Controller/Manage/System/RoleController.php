<?php

declare(strict_types=1);

namespace App\Controller\Manage\System;

use App\Constants\ErrorCode;
use App\Controller\Manage\CommonController;
use App\Model\RoleHasMenu;
use App\Model\User;
use App\Request\RoleCreateRequest;
use App\Request\RoleUpdateRequest;
use App\Request\RoleWithMenusRequest;
use Hyperf\Database\Model\Builder;
use Qingliu\Permission\Models\Permission;
use Qingliu\Permission\Models\Role;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 * 角色管理
 * @package App\Controller\Manage
 */
class RoleController extends CommonController
{
    use MenuTreeGetter;
    /**
     * 创建角色
     * @param RoleCreateRequest $request
     * @return ResponseInterface
     */
    public function create(RoleCreateRequest $request)
    {
        $request->validateResolved();
        $role = Role::create($request->validated());
        return $this->success($role->toArray());
    }

    /**
     * 更新角色描述
     * @param RoleUpdateRequest $request
     * @return ResponseInterface
     */
    public function update(RoleUpdateRequest $request)
    {
        $request->validateResolved();
        $params = $request->validated();
        if ($role = Role::query()->find($params['id'])) {
            $role->describe =  $params['describe'];
            return $role->save() ? $this->success($role->toArray()) : $this->failure(ErrorCode::OPERATE_FAILURE, '保存失败');
        }
        return $this->failure(ErrorCode::NOT_FOUND);
    }

    /**
     * 获取角色列表
     * @return ResponseInterface
     */
    public function lists()
    {
        $name = $this->request->query('name');
        $describe = $this->request->query('describe');
        $role = Role::query()->orderBy('id', 'desc');

        $name && $role->where('name', 'like', "%{$name}%");
        $describe && $role->where('describe', 'like', "%{$describe}%");

        $paginate = $role->paginate($this->limit());
        return $this->success($this->paginate($paginate));
    }

    /**
     * 删除角色
     * @param $id
     * @return ResponseInterface
     * @throws \Exception
     */
    public function delete(int $id)
    {
        // TODO 需要检验角色是否被用户占用
        /** @var Role $role */
        $role = Role::findById($id);
        if ($role) {
            if ($role->delete()) {
                return $this->success();
            } else {
                return $this->message(ErrorCode::OPERATE_FAILURE);
            }
        }
        return $this->message(ErrorCode::NOT_FOUND);
    }

    /**
     * 角色绑定权限
     * @return ResponseInterface
     */
    public function roleWithPermission()
    {
        $rid = (int)$this->request->post('rid');
        $pid = (int)$this->request->post('pid');
        $role = Role::findById($rid);
        $permission = Permission::findById($pid);
        $role->givePermissionTo($permission);

        return $this->success($role->toArray());
    }

    /**
     * 用户绑定角色
     * @return ResponseInterface
     */
    public function userWithRole()
    {
        return $this->userAndRole(true);
    }

    /**
     * 用户与角色分离
     * @return ResponseInterface
     */
    public function userSeparateRole()
    {
        return $this->userAndRole(false);
    }

    /**
     * 用户与角色的关系
     * @param bool $with 是否关联
     * @return ResponseInterface
     */
    protected function userAndRole(bool $with)
    {
        $uid = (int)$this->request->post('uid');
        $rid = (int)$this->request->post('rid');

        $role = Role::findById($rid);
        $user = User::query()->find($uid);
        if ($role && $user) {
            if($with) {
                $user->assignRole($role);
            } else {
                $user->removeRole($role);
            }
            return $this->success();
        } else {
            return $this->failure(ErrorCode::NOT_FOUND, '用户或角色不存在');
        }
    }

    /**
     * 角色关联菜单(会同时更新菜单)
     * @param RoleWithMenusRequest $request
     * @return ResponseInterface
     */
    public function roleWithMenus(RoleWithMenusRequest $request)
    {
        $request->validateResolved();
        $rid = (int)$this->request->post('rid');
        $menuIds = $this->request->post('mid');

        if (RoleHasMenu::assignMenu((int)$rid, $menuIds)) {
            return $this->success();
        }
        return $this->failure(ErrorCode::OPERATE_FAILURE);
    }

    /**
     * 获取角色对应的菜单树
     * @param int $id
     * @return ResponseInterface
     */
    public function menuTree(int $id)
    {
        $treble = !$this->request->query('list');
        $tree = $this->getMenuTree(function (Builder $menu) use ($id) {
            return $menu
                ->leftJoin('role_has_menus as rhm', 'rhm.menu_id', '=', 'menus.id')
                ->where('rhm.role_id', '=', $id);
        }, $treble);
        return $this->success($tree);
    }

    /**
     * 获取角色对应的权限
     * @param int $id
     * @return ResponseInterface
     */
    public function permissions(int $id)
    {
        $role = Role::query()->find($id);
        if ($role) {
            $permissions = $role->permissions->toArray();
            return $this->success(array_column($permissions, 'id'));
        } else {
            return $this->failure(ErrorCode::NOT_FOUND);
        }
    }
}
