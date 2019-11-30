<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateAttachmentAttachment.php 28830 2019-09-29 16:52 chenkeke $
 */

namespace App\Commands\Attachment;

use App\Models\User;
use App\Tools\AttachmentUploadTool;
use App\Events\Attachment\Uploading;
use App\Exceptions\UploadException;
use App\Models\Attachment;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Http\Exception\UploadVerifyException;
use Illuminate\Contracts\Events\Dispatcher;
use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class CreateAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 上传的附件.
     *
     * @var UploadedFileInterface
     */
    public $file;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $isGallery;

    /**
     * 初始化命令参数
     *
     * @param User $actor 执行操作的用户.
     * @param UploadedFileInterface $file
     * @param string $ipAddress 请求来源的IP地址.
     * @param int $isGallery    是否是帖子图片
     */
    public function __construct(
        $actor,
        UploadedFileInterface $file,
        string $ipAddress,
        int $isGallery = 0
    ) {
        $this->actor = $actor;
        $this->file = $file;
        $this->ipAddress = $ipAddress;
        $this->isGallery = $isGallery;
    }

    /**
     * 执行命令
     *
     * @param Dispatcher $events
     * @param AttachmentUploadTool $uploadTool
     * @return Attachment
     * @throws UploadException
     * @throws PermissionDeniedException
     * @throws UploadVerifyException
     */
    public function handle(Dispatcher $events, AttachmentUploadTool $uploadTool)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'attachment.createAttachment');

        // 判断上传的文件是否正常
        if ($this->file->getError()){
            throw new UploadException();
        }

        $uploadTool->upload($this->file, 'attachment');

        $this->events->dispatch(
            new Uploading($this->actor, $this->file)
        );

        $type = [];

        $size = 0;

        $uploadFile = $uploadTool->save($type, $size);

        if (! $uploadFile){
            throw new UploadException();
        }

        $uploadPath = $uploadTool->getUploadPath();

        $uploadName = $uploadTool->getUploadName();

        // 初始附件数据
        $attachment = Attachment::creation(
            $this->actor->id,
            0,
            $this->isGallery,
            $uploadName,
            $uploadPath,
            $this->file->getClientFilename(),
            $this->file->getSize(),
            $this->file->getClientMediaType(),
            0,
            $this->ipAddress
        );

        // 保存附件
        $attachment->save();

        // 调用钩子事件
        $this->dispatchEventsFor($attachment);

        return $attachment;
    }
}
