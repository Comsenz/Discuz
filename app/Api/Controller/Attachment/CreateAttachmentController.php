<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateAttachmentController.php 28830 2019-09-29 16:55 chenkeke $
 */

namespace App\Api\Controller\Attachment;


use App\Api\Serializer\AttachmentSerializer;
use App\Commands\Attachment\CreateAttachment;
use App\Tools\AttachmentUploadTool;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateAttachmentController extends AbstractCreateController
{
    /**
     * 返回的数据字段和格式.
     *
     * @var Serializer
     */
    public $serializer = AttachmentSerializer::class;

    /**
     * 数据操作.
     *
     * @param ServerRequestInterface $request  注入http请求对象
     * @param Document               $document 注入返回数据的文档
     * @return mixed
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取上传的图标
        $file = Arr::get($request->getUploadedFiles(), 'file');

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $uploadTool = $this->app->make(AttachmentUploadTool::class);

        $uploadTool->setFile($file);

        // 处理上传的圈子图片
        $data = $this->bus->dispatch(
            new CreateAttachment($actor, $uploadTool, $ipAddress)
        );

        // 返回结果
        return $data;
    }
}