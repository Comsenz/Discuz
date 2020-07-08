<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Attachment;

use App\Events\Attachment\Saving;
use App\Events\Attachment\Uploaded;
use App\Events\Attachment\Uploading;
use App\Models\Attachment;
use App\Models\User;
use App\Validators\AttachmentValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class CreateAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var UploadedFileInterface
     */
    public $file;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $ipAddress;

    /**
     * 类型：0 附件 1 图片 2 音频 3 视频
     *
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $order;

    /**
     * @param User $actor
     * @param UploadedFileInterface $file
     * @param string $name
     * @param string $ipAddress
     * @param int $type
     * @param int $order
     */
    public function __construct(User $actor, UploadedFileInterface $file, string $name, string $ipAddress, $type, $order = 0)
    {
        $this->actor = $actor;
        $this->file = $file;
        $this->name = $name;
        $this->ipAddress = $ipAddress;
        $this->type = $type;
        $this->order = $order;
    }

    /**
     * @param Dispatcher $events
     * @param AttachmentValidator $validator
     * @param AttachmentUploader $uploader
     * @return Attachment
     * @throws PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(Dispatcher $events, AttachmentValidator $validator, AttachmentUploader $uploader)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'attachment.create.' . $this->type);

        $file = $this->file;

        $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $ext = $ext ? ".$ext" : '';

        $tmpFile = tempnam(storage_path('/tmp'), 'attachment');
        $tmpFileWithExt = $tmpFile . $ext;

        $file->moveTo($tmpFileWithExt);

        try {
            $file = new UploadedFile(
                $tmpFileWithExt,
                $file->getClientFilename(),
                $file->getClientMediaType(),
                $file->getError(),
                true
            );

            // 验证
            $validator->valid(['type' => $this->type, 'size' => $file->getSize(), 'file' => $file]);

            $this->events->dispatch(
                new Uploading($this->actor, $file)
            );

            // 上传
            $uploader->upload($file, $this->type);

            $this->events->dispatch(
                new Uploaded($this->actor, $uploader)
            );

            $attachment = Attachment::build(
                $this->actor->id,
                $this->type,
                $file->hashName(),
                $uploader->getPath(),
                $this->name ?: $file->getClientOriginalName(),
                $file->getSize(),
                $file->getMimeType(),
                $uploader->isRemote(),
                Attachment::APPROVED,
                $this->ipAddress,
                $this->order
            );

            $this->events->dispatch(
                new Saving($attachment, $uploader, $this->actor)
            );

            $attachment->save();

            $this->dispatchEventsFor($attachment);
        } finally {
            @unlink($tmpFile);
            @unlink($tmpFileWithExt);
        }

        return $attachment;
    }
}
