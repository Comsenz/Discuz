<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateAttachmentAttachment.php 28830 2019-09-29 16:52 chenkeke $
 */

namespace App\Commands\Attachment;

use App\Tools\AttachmentUploadTool;
use App\Events\Attachment\Uploading;
use App\Exceptions\UploadException;
use App\Models\Attachment;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class CreateAttachment
{
    use EventsDispatchTrait;
    use AssertPermissionTrait;
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
     * 初始化命令参数
     *
     * @param User $actor 执行操作的用户.
     * @param UploadedFileInterface $file
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct(
        $actor,
        UploadedFileInterface $file,
        string $ipAddress
    ) {
        $this->actor = $actor;
        $this->file = $file;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param EventDispatcher $events
     * @param AttachmentUploadTool $uploadTool
     * @return Attachment
     * @throws UploadException
     */
    public function handle(EventDispatcher $events, AttachmentUploadTool $uploadTool)
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

        if (!$uploadFile){
            throw new UploadException();
        }

        $uploadPath = $uploadTool->getUploadPath();

        $uploadName = $uploadTool->getUploadName();

        // 初始附件数据
        $attachment = Attachment::creation(
            $this->actor->id,
            0,
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