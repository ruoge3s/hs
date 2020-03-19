<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $module 模块
 * @property string $path 文件地址
 * @property string $extname 扩展名
 * @property int $existed 是否存在
 * @property int $public 是否公开(在public目录还是在storage目录)
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at
 */
class UploadLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'upload_logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'module', 'extname', 'existed', 'public'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'existed' => 'integer', 'public' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 创建记录
     * @param string $path
     * @param string $extname
     * @param string $module
     * @param bool $existed
     * @param bool $public
     * @return static|null
     */
    public static function record(string $path, string $extname = '', string $module = '', bool $existed = true, bool $public=true)
    {
        $static = new static([
            'path'      => $path,
            'module'    => $module,
            'extname'   => $extname,
            'existed'   => $existed,
            'public'    => $public
        ]);
        return $static->save() ? $static : null;
    }

    /**
     * 获得图片相对项目的真实路径
     * @return string
     */
    public function realpath()
    {
        return DIRECTORY_SEPARATOR . $this->public ? 'public' : 'storage' . $this->path;
    }
}