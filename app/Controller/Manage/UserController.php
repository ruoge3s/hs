<?php

declare(strict_types=1);

namespace App\Controller\Manage;

use App\Constants\ErrorCode;
use App\Controller\Manage\System\MenuTreeGetter;
use App\Logic\AccessToken;
use App\Model\User;
use App\Request\AdminLoginRequest;
use App\Request\AdminUserRegisterRequest;
use App\Request\AdminUserUpdateRequest;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Collection;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Database\Model\Builder;

/**
 * Class UserController
 * 用户管理
 * @package App\Controller\Manage
 */
class UserController extends CommonController
{
    use MenuTreeGetter;

    /**
     * 用户登录逻辑
     * @param AdminLoginRequest $request
     * @return ResponseInterface
     */
    public function login(AdminLoginRequest $request)
    {
        $request->validateResolved();
        $username = $request->validated()['username'];
        $password = $request->validated()['password'];
        $remember = $request->validated()['remember'] ?? 0;
        /** @var User $user */
        $user = User::query()
            ->where(function(Builder $query) use ($username) {
                $query
                    ->where('username', '=', $username)
                    ->orWhere('email', '=', $username);
            })
            ->where('enable', '=', User::YES)
            ->first(['id', 'username', 'nickname', 'password', 'email']);
        if ($user && $user->validatePassword($password)) {
            $ttl = ($remember ? 7 : 1) * 86400;
            // TODO 查询相关数据放在登录的TOKEN中

            $token = (new AccessToken(AccessToken::TYPE_ADMIN, [
                'id'        => $user->id,
                'ip'        => Arr::first($request->getHeader('x-real-ip')),
                'loginTime' => date('Y-m-d H:i:s')
            ]))->load(['ttl' => $ttl])->generate();
            return $this->success([
                'token' => $token,
                'ttl'   => $ttl,
            ]);
        }
        return $this->failure(ErrorCode::AUTH_FAILURE_LOGIN);
    }

    /**
     * 退出登录
     * @return ResponseInterface
     */
    public function logout()
    {
        $token = Arr::first($this->request->getHeader('Authorization'));
        if (AccessToken::free($token)) {
            return $this->success();
        }
        return $this->failure(ErrorCode::OPERATE_FAILURE);
    }

    /**
     * 获取当前用户的信息
     * @return ResponseInterface
     */
    public function info()
    {
        $info = $this->admin()->toArray();
        $info['avatar'] = env('APP_URL') . '/assets/header.png';
        return $this->success($info);
    }

    /**
     * 获取某个用户的信息
     * @param int $id
     * @return ResponseInterface
     */
    public function detail(int $id)
    {
        $user = User::findFromCache($id);
        if ($user) {
            return $this->success($user->toArray());
        }
        return $this->failure(ErrorCode::NOT_FOUND);
    }

    /**
     * 查询用户列表
     * @return ResponseInterface
     */
    public function lists()
    {
        $username   = $this->request->query('username');
        $nickname   = $this->request->query('nickname');
        $email      = $this->request->query('email');
        $enable     = $this->request->query('enable', '');

        $user = User::query()->orderBy('id', 'desc');
        $username   && $user->where('username', 'like', "%{$username}%");
        $nickname   && $user->where('nickname', 'like', "%{$nickname}%");
        $email      && $user->where('email', 'like', "%{$email}%");
        $enable === '' || $user->where('enable', '=', (int)$enable);

        return $this->success($this->paginate($user->paginate($this->limit())));
    }

    /**
     * 创建用户
     * @param AdminUserRegisterRequest $request
     * @return ResponseInterface
     */
    public function create(AdminUserRegisterRequest $request)
    {
        $request->validateResolved();
        $data = $request->validated();
        $user = new User();
        $user->resetPassword($data['password']);
        $user->enable = User::NO;
        if ($user->fill($data)->save()) {
            return $this->success($user->toArray());
        } else {
            return $this->message(ErrorCode::OPERATE_FAILURE);
        }
    }

    /**
     * 更新用户
     * @param AdminUserUpdateRequest $request
     * @return ResponseInterface
     */
    public function update(AdminUserUpdateRequest $request)
    {
        $request->validateResolved();
        $data = $request->validated();

        $user = User::findFromCache($data['id']);
        if ($user) {
            $data['password'] && $user->resetPassword($data['password']); // 填写了新密码才更新
            if ($user->fill($data)->save()) {
                return $this->success($user->toArray());
            } else {
                return $this->message(ErrorCode::OPERATE_FAILURE);
            }
        }
        return $this->failure(ErrorCode::NOT_FOUND);
    }

    /**
     * 获取用户所有的角色名
     * @param int $uid
     * @return ResponseInterface
     */
    public function userRoleNames(int $uid)
    {
        $user = User::query()->find($uid);
        /** @var Collection $data */
        $data = $user->roles->pluck('describe', 'id');
        return $this->success($data->toArray());
    }

    /**
     * 获取用户所有权限
     * @param int $uid
     * @return ResponseInterface
     */
    public function userAllPermissions(int $uid)
    {
        $user = User::query()->find($uid);
        /** @var Collection $data */
        $data = $user->getAllPermissions();
        return $this->success($data->toArray());
    }

    /**
     * 获取用户对应的菜单树
     * @return ResponseInterface
     */
    public function menuTree()
    {
        if ($this->admin()->isAdministrator()) {
            $filter = null;
        } else {
            $rids = $this->admin()->roles->pluck('id')->toArray();
            if (!$rids) {
                return $this->success([]); // 没有分配角色的用户无菜单,返回空菜单数据
            }
            $filter = function (Builder $menu) use ($rids) {
                return $menu
                    ->leftJoin('role_has_menus as rhm', 'rhm.menu_id', '=', 'menus.id')
                    ->where(function(Builder $query) use ($rids) {
                        foreach ($rids as $mid) {
                            $query->orWhere('rhm.role_id', '=', $mid);
                        }
                    });
            };
        }
        $tree = $this->getMenuTree($filter);
        return $this->success($tree);
    }

    /**
     * 禁用用户
     * @param int $id
     * @return ResponseInterface
     */
    public function disable(int $id)
    {
        return $this->active($id, User::NO, function () {
            return ErrorCode::SUCCESS;
        });
    }

    /**
     * 启用用户
     * @param int $id
     * @return ResponseInterface
     */
    public function enable(int $id)
    {
        return $this->active($id, User::YES, function () {
            return ErrorCode::SUCCESS;
        });
    }

    /**
     * 用户状态操作
     * @param $id
     * @param int $value
     * @param callable|null $callable
     * @return ResponseInterface
     */
    protected function active($id, int $value, callable $callable=null)
    {
        $user = User::findFromCache($id);
        if ($user && $user->id > 1) {
            if ($callable && $code = $callable()) {
                if (ErrorCode::SUCCESS != $code) return $this->message($code);
            }
            return $user->fill(['enable' => $value])->save()
                ? $this->success()
                : $this->message(ErrorCode::OPERATE_FAILURE);
        }
        return $this->message(ErrorCode::NOT_FOUND);
    }

}
