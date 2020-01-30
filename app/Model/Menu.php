<?php

declare (strict_types=1);
namespace App\Model;

use \Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $describe
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pid', 'name', 'describe'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'pid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function son(): HasMany
    {
        return $this->hasMany(Menu::class, 'pid', 'id');
    }
}
