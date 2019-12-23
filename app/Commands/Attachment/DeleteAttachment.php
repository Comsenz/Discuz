<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Attachment;

use App\Events\Attachment\Deleted;
use App\Events\Attachment\Deleting;
use App\Models\User;
use App\Repositories\AttachmentRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

class DeleteAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The uuid of the attachment to delete.
     *
     * @var string
     */
    public $attachmentUuid;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param int $attachmentUuid
     * @param User $actor
     * @param array $data
     */
    public function __construct($attachmentUuid, User $actor, array $data = [])
    {
        $this->attachmentUuid = $attachmentUuid;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(Dispatcher $events, AttachmentRepository $attachments)
    {
        $this->events = $events;

        $attachment = $attachments->findOrFail($this->attachmentUuid, $this->actor);

        $this->assertCan($this->actor, 'delete', $attachment);

        $this->events->dispatch(
            new Deleting($attachment, $this->actor, $this->data)
        );

        $attachment->raise(new Deleted($attachment));
        $attachment->delete();

        // 删除源文件
        $filePath = storage_path('app/attachment/' . $attachment->attachment);
        unlink($filePath);

        // 如果是帖子图片，删除有可能生成的缩略图
        if ($attachment->is_gallery) {
            $thumb = Str::replaceLast('.', '_thumb.', $filePath);

            if (file_exists($thumb)) {
                unlink($thumb);
            }
        }

        $this->dispatchEventsFor($attachment, $this->actor);

        return $attachment;
    }
}
