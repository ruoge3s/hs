<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 * @property int $role_id
 * @property int $menu_id
 */
class RoleHasMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_has_menus';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'menu_id' => 'integer'];

    public $timestamps = false; // 关闭时间戳

    /**
     * 给角色添加菜单(同时删除旧的菜单)
     * @param int $roleId
     * @param array $menuIds
     * @return bool
     */
    public static function assignMenu(int $roleId, array $menuIds)
    {
        static::query()
            ->where('role_id', '=', $roleId)
            ->delete();

        $data = array_map(function ($id) use ($roleId) {
            return ['role_id' => $roleId, 'menu_id' => $id ];
        }, $menuIds);

        return Db::table('role_has_menus')->insert($data);
    }
}
