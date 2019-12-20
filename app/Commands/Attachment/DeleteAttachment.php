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

class DeleteAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the attachment to delete.
     *
     * @var int
     */
    public $attachmentId;

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
     * @param int $attachmentId
     * @param User $actor
     * @param array $data
     */
    public function __construct($attachmentId, User $actor, array $data = [])
    {
        $this->attachmentId = $attachmentId;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(Dispatcher $events, AttachmentRepository $attachments)
    {
        $this->events = $events;

        $attachment = $attachments->findOrFail($this->attachmentId, $this->actor);

        $this->assertCan($this->actor, 'delete', $attachment);

        $this->events->dispatch(
            new Deleting($attachment, $this->actor, $this->data)
        );

        $attachment->raise(new Deleted($attachment));
        $attachment->delete();

        $this->dispatchEventsFor($attachment, $this->actor);

        return $attachment;
    }
}
