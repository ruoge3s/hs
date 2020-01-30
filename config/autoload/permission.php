<?php

return [
    'models' => [
        'permission'    => Qingliu\Permission\Models\Permission::class,
        'role'          => Qingliu\Permission\Models\Role::class,
    ],
    'table_names' => [
        'roles'                 => 'roles',
        'permissions'           => 'permissions',
        'model_has_permissions' => 'user_has_permissions',
        'model_has_roles'       => 'user_has_roles',
        'role_has_permissions'  => 'role_has_permissions',
    ],
    'column_names' => [
        'model_morph_key' => 'user_id',
    ],
    'display_permission_in_exception' => false,
    'cache' => [
        'expiration_time'   => 86400,
        'key'               => 'qingliu.permission.cache',
        'model_key'         => 'name',
        'store'             => 'default',
    ],
];
