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

use Hyperf\HttpServer\Router\Router;
use App\Controller\Manage;
use App\Middleware\AdminPermissionMiddleware;
use App\Middleware\AdminAuthMiddleware;


Router::get('/', [App\Controller\HomeController::class, 'index']);

// 用户登录
Router::post('/manager/user/login', [Manage\UserController::class, 'login']);

// 管理后台相关接口
Router::addGroup('/manager', function (){
    // 角色管理
    Router::post('/system/role/create', [Manage\System\RoleController::class, 'create']);
    Router::get('/system/role/lists', [Manage\System\RoleController::class, 'lists']);
    Router::post('/system/role/update', [Manage\System\RoleController::class, 'update']);
    Router::post('/system/role/delete/{id}', [Manage\System\RoleController::class, 'delete']);
    Router::post('/system/role/with/permission', [Manage\System\RoleController::class, 'roleWithPermission']);
    Router::post('/system/role/with/menus', [Manage\System\RoleController::class, 'roleWithMenus']);
    Router::get('/system/role/{id}/menu/tree', [Manage\System\RoleController::class, 'menuTree']);
    Router::get('/system/role/{id}/permissions', [Manage\System\RoleController::class, 'permissions']);
    Router::post('/system/user/with/role', [Manage\System\RoleController::class, 'userWithRole']);
    Router::post('/system/user/separate/role', [Manage\System\RoleController::class, 'userSeparateRole']);

    // 接口权限管理
    Router::post('/system/permission/create', [Manage\System\PermissionController::class, 'create']);
    Router::get('/system/permission/lists', [Manage\System\PermissionController::class, 'lists']);
    Router::get('/system/permission/map', [Manage\System\PermissionController::class, 'map']);
    Router::post('/system/permission/update', [Manage\System\PermissionController::class, 'update']);
    Router::post('/system/permission/delete/{id}', [Manage\System\PermissionController::class, 'delete']);
    Router::post('/system/permission/with/roles', [Manage\System\PermissionController::class, 'permissionWithRoles']);
    Router::post('/system/permission/separate/role', [Manage\System\PermissionController::class, 'permissionSeparateRole']);
    Router::post('/system/user/with/permissions', [Manage\System\PermissionController::class, 'userWithPermissions']);

    // 接口分组管理
    Router::post('/system/permission/group/create', [Manage\System\PermissionGroupController::class, 'create']);
    Router::post('/system/permission/group/update', [Manage\System\PermissionGroupController::class, 'update']);
    Router::get('/system/permission/group/lists', [Manage\System\PermissionGroupController::class, 'lists']);
    Router::post('/system/permission/group/delete/{id}', [Manage\System\PermissionGroupController::class, 'delete']);
    Router::get('/system/permission/group/map', [Manage\System\PermissionGroupController::class, 'map']);

    // 菜单管理
    Router::post('/system/menu/create', [Manage\System\MenuController::class, 'create']);
    Router::post('/system/menu/delete/{id}', [Manage\System\MenuController::class, 'delete']);
    Router::post('/system/menu/update', [Manage\System\MenuController::class, 'update']);
    Router::get('/system/menu/tree', [Manage\System\MenuController::class, 'tree']);

    // 用户
    Router::get('/user/role/names/{uid}', [Manage\UserController::class, 'userRoleNames']);
    Router::get('/user/permissions/{uid}', [Manage\UserController::class, 'userAllPermissions']);
    Router::get('/user/detail/{id}', [Manage\UserController::class, 'detail']);
    Router::get('/user/lists', [Manage\UserController::class, 'lists']);
    Router::post('/user/create', [Manage\UserController::class, 'create']);
    Router::post('/user/update', [Manage\UserController::class, 'update']);
    Router::post('/user/enable/{id}', [Manage\UserController::class, 'enable']);
    Router::post('/user/disable/{id}', [Manage\UserController::class, 'disable']);

}, [
    'middleware' => [
        AdminAuthMiddleware::class,
        AdminPermissionMiddleware::class,
    ]
]);
Router::addGroup('/manager', function (){ // 用户个人的操作不验证接口权限
    Router::get('/user/info', [Manage\UserController::class, 'info']);
    Router::get('/user/menu/tree', [Manage\UserController::class, 'menuTree']);
    Router::post('/user/logout', [Manage\UserController::class, 'logout']);
}, [
    'middleware' => [
        AdminAuthMiddleware::class,
    ]
]);
