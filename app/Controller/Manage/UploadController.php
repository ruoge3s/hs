<?php
declare(strict_types=1);

namespace App\Controller\Manage;

use App\Constants\ErrorCode;
use App\Request\UploadImageRequest;
use App\Request\UploadMediaRequest;
use App\Request\UploadZipRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UploadController
 * 文件上传处理控制器
 * @package App\Controller\Manage
 */
class UploadController extends CommonController
{
    /**
     * 上传图片，响应图片的ID和path
     * @param UploadImageRequest $request
     * @return ResponseInterface
     */
    public function image(UploadImageRequest $request)
    {
        $request->validateResolved();
        $log = $this->uploadImagesHandler('image', true);
        if ($log) {
            return $this->success(['id' => $log->id, 'path' => $log->path]);
        } else {
            return $this->failure(ErrorCode::OPERATE_FAILURE, '图片文件存储失败');
        }
    }

    /**
     * 上传zip包和tar包
     * @param UploadZipRequest $request
     * @return ResponseInterface
     */
    public function zip(UploadZipRequest $request)
    {
        $request->validateResolved();
        $log = $this->uploadFileHandler('zip', true);
        if ($log) {
            return $this->success(['id' => $log->id, 'path' => $log->path]);
        } else {
            return $this->failure(ErrorCode::OPERATE_FAILURE, '压缩文件存储失败');
        }
    }

    /**
     * 上传多媒体文件主要为音频和视频
     * @param UploadMediaRequest $request
     * @return ResponseInterface
     */
    public function media(UploadMediaRequest $request)
    {
        $request->validateResolved();
        $log = $this->uploadFileHandler('media', true);
        if ($log) {
            return $this->success(['id' => $log->id, 'path' => $log->path]);
        } else {
            return $this->failure(ErrorCode::OPERATE_FAILURE, '音/视频文件存储失败');
        }
    }
}