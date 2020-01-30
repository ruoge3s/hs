<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property int $gid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class PermissionHasGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_has_group';
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
    protected $casts = ['id' => 'int', 'gid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public $timestamps = false;

    /**
     * 创建一个关系，绑定permission与分组
     * @param int $pid
     * @param int $gid
     * @return bool
     */
    public static function relate(int $pid, int $gid)
    {
        $phg = static::query()->findOrNew($pid);
        $phg->id || $phg->id = $pid;
        $phg->gid = $gid;
        return $phg->save();
    }
}
