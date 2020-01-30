<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;
use Qingliu\Permission\Traits\HasRoles;
/**
 * @property int $id
 * @property string $username
 * @property string $nickname
 * @property string $email
 * @property string $password
 * @property int $enable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method User givePermissionTo(...$permissions)
 * @method \Hyperf\Utils\Collection getRoleNames()
 * @method \Hyperf\Utils\Collection getAllPermissions()
 * @method static assignRole(...$roles)
 * @method removeRole($role)
 * @method bool can($permission)
 */
class User extends Model implements CacheableInterface
{
    use Cacheable;
    use HasRoles;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'nickname', 'email', 'enable'];

    protected $hidden = ['password'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'enable' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 判断用户是否是超级管理员
     * @return bool
     */
    public function isAdministrator()
    {
        // TODO 校验角色
        return $this->id == 1;
    }
    /**
     * @param $password
     * @return $this
     */
    public function resetPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }
    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }
}
