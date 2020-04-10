<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Attachment;

use App\Events\Attachment\Deleted;
use App\Events\Attachment\Deleting;
use App\Exceptions\TranslatorException;
use App\Models\User;
use App\Repositories\AttachmentRepository;
use App\Tools\AttachmentUploadTool;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

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

    /**
     * @param Dispatcher $events
     * @param AttachmentRepository $attachments
     * @param AttachmentUploadTool $uploadTool
     * @return \App\Models\Attachment
     * @throws TranslatorException
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(Dispatcher $events, AttachmentRepository $attachments, AttachmentUploadTool $uploadTool)
    {
        $this->events = $events;

        $attachment = $attachments->findOrFail($this->attachmentUuid, $this->actor);

        $this->assertCan($this->actor, 'delete', $attachment);

        $this->events->dispatch(
            new Deleting($attachment, $this->actor, $this->data)
        );

        $attachment->raise(new Deleted($attachment));

        // 删除源文件
        $result = $uploadTool->delete($attachment);

        if ($result) {
            $attachment->delete();
        } else {
            throw new TranslatorException('post_attachment_delete_error');
        }

        $this->dispatchEventsFor($attachment, $this->actor);

        return $attachment;
    }
}
