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

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Helper\Str;
use App\Model\UploadLog;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 构建分页数据响应结果
     * @param LengthAwarePaginatorInterface $paginate
     * @param callable|null $callback 对数据列表进行额外的处理
     * @return array
     */
    public function paginate(LengthAwarePaginatorInterface $paginate, callable $callback=null)
    {
        return [
            'items'     => is_callable($callback) ? $callback($paginate->items()): $paginate->items(),
            'paginate'  => [
                'page'  => $paginate->currentPage(),
                'size'  => $paginate->perPage(),
                'total' => $paginate->total(),
            ],
        ];
    }

    /**
     * 更简便的分页数据响应
     * @param Builder $builder
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function simpleResponse(Builder $builder)
    {
        return $this->success($this->paginate($builder->paginate($this->limit())));
    }

    /**
     * 分页数据每页返回的数据数量，默认10条
     * @param int $max
     * @return int
     */
    public function limit($max=100)
    {
        $size = (int)$this->request->query('limit', 10);
        return $size <= $max ? $size : $max;
    }

    /**
     * 响应的数据
     * @param int $code
     * @param array $data
     * @param string|null $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function message(int $code, array $data=null, string $message=null)
    {
        $info = [
            'code'      => $code,
            'message'   => $message ?? ErrorCode::getMessage($code),
        ];
        is_null($data) || $info['data'] = $data;
        return $this->response->json($info);
    }

    /**
     * 业务逻辑处理正常是响应的数据
     * @param array $data
     * @param null $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success(array $data=null, $message=null)
    {
        return $this->message(ErrorCode::SUCCESS, $data, $message);
    }

    /**
     * 增加错误方法
     * @param int $code
     * @param null $message
     * @param array|null $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function failure(int $code, $message=null, array $data=null)
    {
        return $this->message($code, $data, $message);
    }

    /**
     * 文件处理器
     * @param string $name
     * @param string $moduleName
     * @param bool $public
     * @return UploadLog|null
     */
    protected function fileHandler(string $name, string $moduleName, bool $public=true)
    {
        $file = $this->request->file($name);
        $home = '/' . $moduleName . '/' . date('Ymd');
        $filename =  Str::unique(md5($name)) . '.' . $this->request->file($name)->getExtension();
        $absolutePath = BASE_PATH . '/' . ($public ? 'public' : 'storage') .  $home;
        is_dir($absolutePath) || mkdir($absolutePath, 0777, true);

        $targetPath = $absolutePath . '/' . $filename;
        $file->moveTo($targetPath);
        if ($file->isMoved()) {
            chmod($targetPath, 0777);
            return UploadLog::record($home . '/' . $filename, $this->request->file($name)->getExtension(), $moduleName, $public);
        } else {
            return null;
        }
    }

    /**
     * 删除上传的文件
     * @param $path
     * @param bool $public
     * @return bool
     */
    protected function deleteFile($path, bool $public=true)
    {
        return unlink(BASE_PATH. '/' . ($public ? 'public' : 'storage') . '/' . $path);
    }

    /**
     * 专门处理图片文件
     * @param string $name
     * @param bool $public
     * @param string $suffix
     * @return UploadLog|null
     */
    protected function uploadImagesHandler(string $name, bool $public=true, string $suffix = '')
    {
        // 图片类型验证由requestFrom验证
        return $this->fileHandler($name, $this->moduleName() . '/' . 'images' . $suffix, $public);
    }

    /**
     * 专门处理其他类型的文件
     * @param string $name
     * @param bool $public
     * @param string $suffix
     * @return UploadLog|null
     */
    protected function uploadFileHandler(string $name, bool $public=true, string $suffix = '')
    {
        return $this->fileHandler($name, $this->moduleName() . '/' . 'file' . $suffix, $public);
    }

    /**
     * 定义当前模块名称
     * @return string
     */
    abstract function moduleName() : string ;
}
