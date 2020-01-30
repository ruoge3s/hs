<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

/**
 * 生产环境可用的seeder
 * Class Init
 */
class Init extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建基本的菜单
        \Hyperf\DbConnection\Db::table('menus')->insert([
            ['id' => 1, 'pid' => 0, 'name' => 'system-manage', 'describe' => '系统管理', 'created_at' => '2020-01-17 11:04:57', 'updated_at' => '2020-01-17 11:04:57', ],
            ['id' => 3, 'pid' => 1, 'name' => 'user-manage', 'describe' => '用户管理', 'created_at' => '2020-01-17 11:05:29', 'updated_at' => '2020-01-17 11:05:29', ],
            ['id' => 8, 'pid' => 3, 'name' => 'user-list', 'describe' => '用户列表', 'created_at' => '2020-01-17 11:07:19', 'updated_at' => '2020-01-17 11:07:19', ],
            ['id' => 9, 'pid' => 3, 'name' => 'role-manage', 'describe' => '角色管理', 'created_at' => '2020-01-17 11:51:38', 'updated_at' => '2020-01-17 11:51:38', ],
            ['id' => 10, 'pid' => 3, 'name' => 'user-detail', 'describe' => '用户详情', 'created_at' => '2020-01-17 11:52:22', 'updated_at' => '2020-01-17 11:52:22', ],
            ['id' => 11, 'pid' => 1, 'name' => 'interface-auth', 'describe' => '接口权限', 'created_at' => '2020-01-17 11:53:25', 'updated_at' => '2020-01-17 11:53:25', ],
            ['id' => 12, 'pid' => 11, 'name' => 'interface-group', 'describe' => '接口分组', 'created_at' => '2020-01-17 11:53:41', 'updated_at' => '2020-01-17 11:53:41', ],
            ['id' => 13, 'pid' => 11, 'name' => 'interface-list', 'describe' => '接口列表', 'created_at' => '2020-01-17 11:53:58', 'updated_at' => '2020-01-17 11:53:58', ],
            ['id' => 15, 'pid' => 1, 'name' => 'global-menu', 'describe' => '全局菜单', 'created_at' => '2020-01-17 11:56:48', 'updated_at' => '2020-01-17 11:56:48', ],
        ]);

        // 创建角色
        \Hyperf\DbConnection\Db::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'describe' => '超级管理员', 'created_at' => '2020-01-17 13:46:24', 'updated_at' => '2020-01-17 13:46:24', ],
        ]);

        // 创建权限
        \Hyperf\DbConnection\Db::table('permissions')->insert([
            ['id' => 1, 'name' => '/manager/system/role/create', 'describe' => '创建角色', 'created_at' => '2020-01-17 13:51:22', 'updated_at' => '2020-01-17 13:51:22', ],
            ['id' => 2, 'name' => '/manager/system/role/lists', 'describe' => '角色列表', 'created_at' => '2020-01-17 13:56:54', 'updated_at' => '2020-01-17 13:56:54', ],
            ['id' => 3, 'name' => '/manager/system/role/update', 'describe' => '更新角色', 'created_at' => '2020-01-17 13:59:50', 'updated_at' => '2020-01-17 13:59:50', ],
            ['id' => 4, 'name' => '/manager/system/role/delete/{id}', 'describe' => '删除角色', 'created_at' => '2020-01-17 14:00:04', 'updated_at' => '2020-01-17 14:00:04', ],
            ['id' => 5, 'name' => '/manager/system/role/with/permission', 'describe' => '角色绑定权限', 'created_at' => '2020-01-17 14:00:33', 'updated_at' => '2020-01-17 14:00:33', ],
            ['id' => 6, 'name' => '/manager/system/role/with/menus', 'describe' => '角色绑定菜单', 'created_at' => '2020-01-17 14:00:54', 'updated_at' => '2020-01-17 14:00:54', ],
            ['id' => 7, 'name' => '/manager/system/role/{id}/menu/tree', 'describe' => '获取角色权限树', 'created_at' => '2020-01-17 14:01:34', 'updated_at' => '2020-01-17 14:01:34', ],
            ['id' => 8, 'name' => '/manager/system/role/{id}/permissions', 'describe' => '获取角色授权接口数据', 'created_at' => '2020-01-17 14:02:05', 'updated_at' => '2020-01-17 14:02:05', ],
            ['id' => 9, 'name' => '/manager/system/user/with/role', 'describe' => '用户绑定角色', 'created_at' => '2020-01-17 14:02:32', 'updated_at' => '2020-01-17 14:02:32', ],
            ['id' => 10, 'name' => '/manager/system/user/separate/role', 'describe' => '用户解绑角色', 'created_at' => '2020-01-17 14:03:03', 'updated_at' => '2020-01-17 14:03:03', ],
            ['id' => 11, 'name' => '/manager/system/permission/create', 'describe' => '创建接口权限', 'created_at' => '2020-01-17 14:03:24', 'updated_at' => '2020-01-17 14:03:24', ],
            ['id' => 12, 'name' => '/manager/system/permission/lists', 'describe' => '获取接口列表', 'created_at' => '2020-01-17 14:03:49', 'updated_at' => '2020-01-17 14:03:49', ],
            ['id' => 13, 'name' => '/manager/system/permission/map', 'describe' => '获取接口权限树', 'created_at' => '2020-01-17 14:04:09', 'updated_at' => '2020-01-17 14:04:09', ],
            ['id' => 14, 'name' => '/manager/system/permission/update', 'describe' => '更新接口权限', 'created_at' => '2020-01-17 14:04:28', 'updated_at' => '2020-01-17 14:04:28', ],
            ['id' => 15, 'name' => '/manager/system/permission/delete/{id}', 'describe' => '删除接口权限', 'created_at' => '2020-01-17 14:04:44', 'updated_at' => '2020-01-17 14:04:44', ],
            ['id' => 16, 'name' => '/manager/system/permission/with/roles', 'describe' => '接口权限绑定角色', 'created_at' => '2020-01-17 14:05:23', 'updated_at' => '2020-01-17 14:05:23', ],
            ['id' => 17, 'name' => '/manager/system/permission/separate/role', 'describe' => '角色解绑权限', 'created_at' => '2020-01-17 14:05:59', 'updated_at' => '2020-01-17 14:05:59', ],
            ['id' => 18, 'name' => '/manager/system/user/with/permissions', 'describe' => '用户关联接口权限', 'created_at' => '2020-01-17 14:06:21', 'updated_at' => '2020-01-17 14:06:21', ],
            ['id' => 19, 'name' => '/manager/system/permission/group/create', 'describe' => '创建接口分组', 'created_at' => '2020-01-17 14:06:53', 'updated_at' => '2020-01-17 14:06:53', ],
            ['id' => 20, 'name' => '/manager/system/permission/group/update', 'describe' => '更新接口分组', 'created_at' => '2020-01-17 14:07:11', 'updated_at' => '2020-01-17 14:07:11', ],
            ['id' => 21, 'name' => '/manager/system/permission/group/lists', 'describe' => '获取接口分组列表', 'created_at' => '2020-01-17 14:07:41', 'updated_at' => '2020-01-17 14:07:41', ],
            ['id' => 22, 'name' => '/manager/system/permission/group/delete/{id}', 'describe' => '删除权限分组', 'created_at' => '2020-01-17 14:08:19', 'updated_at' => '2020-01-17 14:08:19', ],
            ['id' => 23, 'name' => '/manager/system/permission/group/map', 'describe' => '获取接口分组Map', 'created_at' => '2020-01-17 14:09:01', 'updated_at' => '2020-01-17 14:09:01', ],
            ['id' => 24, 'name' => '/manager/system/menu/create', 'describe' => '创建菜单', 'created_at' => '2020-01-17 14:10:12', 'updated_at' => '2020-01-17 14:10:12', ],
            ['id' => 25, 'name' => '/manager/system/menu/delete/{id}', 'describe' => '删除菜单', 'created_at' => '2020-01-17 14:10:36', 'updated_at' => '2020-01-17 14:10:36', ],
            ['id' => 26, 'name' => '/manager/system/menu/update', 'describe' => '更新菜单', 'created_at' => '2020-01-17 14:10:53', 'updated_at' => '2020-01-17 14:10:53', ],
            ['id' => 27, 'name' => '/manager/system/menu/tree', 'describe' => '获取菜单树', 'created_at' => '2020-01-17 14:11:09', 'updated_at' => '2020-01-17 14:11:09', ],
            ['id' => 28, 'name' => '/manager/user/role/names/{uid}', 'describe' => '获取用户的角色列表', 'created_at' => '2020-01-17 14:12:06', 'updated_at' => '2020-01-17 14:12:06', ],
            ['id' => 29, 'name' => '/manager/user/permissions/{uid}', 'describe' => '用户权限列表', 'created_at' => '2020-01-17 14:13:11', 'updated_at' => '2020-01-17 14:13:11', ],
            ['id' => 30, 'name' => '/manager/user/detail/{id}', 'describe' => '获取用户详情', 'created_at' => '2020-01-17 14:13:33', 'updated_at' => '2020-01-17 14:13:33', ],
            ['id' => 31, 'name' => '/manager/user/lists', 'describe' => '获取用户列表', 'created_at' => '2020-01-17 14:14:02', 'updated_at' => '2020-01-17 14:14:02', ],
            ['id' => 32, 'name' => '/manager/user/create', 'describe' => '用户创建', 'created_at' => '2020-01-17 14:14:22', 'updated_at' => '2020-01-17 14:14:22', ],
            ['id' => 33, 'name' => '/manager/user/update', 'describe' => '更新用户', 'created_at' => '2020-01-17 14:14:35', 'updated_at' => '2020-01-17 14:14:35', ],
            ['id' => 34, 'name' => '/manager/user/enable/{id}', 'describe' => '启用用户', 'created_at' => '2020-01-17 14:14:59', 'updated_at' => '2020-01-17 14:14:59', ],
            ['id' => 35, 'name' => '/manager/user/disable/{id}', 'describe' => '禁用用户', 'created_at' => '2020-01-17 14:15:12', 'updated_at' => '2020-01-17 14:15:12', ],
        ]);

        \Hyperf\DbConnection\Db::table('permission_group')->insert([
            ['id' => 1, 'name' => '用户管理', 'sort' => 0, 'created_at' => '2020-01-17 13:49:18', 'updated_at' => '2020-01-17 13:57:44', ],
            ['id' => 2, 'name' => '角色管理', 'sort' => 0, 'created_at' => '2020-01-17 13:49:32', 'updated_at' => '2020-01-17 13:57:51', ],
            ['id' => 3, 'name' => '接口权限', 'sort' => 0, 'created_at' => '2020-01-17 13:49:54', 'updated_at' => '2020-01-17 13:58:27', ],
            ['id' => 4, 'name' => '全局菜单', 'sort' => 0, 'created_at' => '2020-01-17 13:50:05', 'updated_at' => '2020-01-17 13:58:52', ],
        ]);

        \Hyperf\DbConnection\Db::table('permission_has_group')->insert([
            ['id' => 1, 'gid' => 2, ], ['id' => 2, 'gid' => 2, ], ['id' => 3, 'gid' => 2, ],
            ['id' => 4, 'gid' => 2, ], ['id' => 5, 'gid' => 2, ], ['id' => 6, 'gid' => 2, ],
            ['id' => 7, 'gid' => 2, ], ['id' => 8, 'gid' => 2, ], ['id' => 9, 'gid' => 1, ],
            ['id' => 10, 'gid' => 1, ], ['id' => 11, 'gid' => 3, ], ['id' => 12, 'gid' => 3, ],
            ['id' => 13, 'gid' => 3, ], ['id' => 14, 'gid' => 3, ], ['id' => 15, 'gid' => 3, ],
            ['id' => 16, 'gid' => 3, ], ['id' => 17, 'gid' => 2, ], ['id' => 18, 'gid' => 1, ],
            ['id' => 19, 'gid' => 3, ], ['id' => 20, 'gid' => 3, ], ['id' => 21, 'gid' => 3, ],
            ['id' => 22, 'gid' => 3, ], ['id' => 23, 'gid' => 3, ], ['id' => 24, 'gid' => 4, ],
            ['id' => 25, 'gid' => 4, ], ['id' => 26, 'gid' => 4, ], ['id' => 27, 'gid' => 4, ],
            ['id' => 28, 'gid' => 1, ], ['id' => 29, 'gid' => 1, ], ['id' => 30, 'gid' => 1, ],
            ['id' => 31, 'gid' => 1, ], ['id' => 32, 'gid' => 1, ], ['id' => 33, 'gid' => 1, ],
            ['id' => 34, 'gid' => 1, ], ['id' => 35, 'gid' => 1, ],
        ]);

    }
}
