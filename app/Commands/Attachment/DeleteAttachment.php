<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Attachment;

use App\Events\Attachment\Deleted;
use App\Events\Attachment\Deleting;
use App\Models\Attachment;
use App\Models\User;
use App\Repositories\AttachmentRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The id of the attachment to delete.
     *
     * @var string
     */
    public $attachmentId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
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

    /**
     * @param Dispatcher $events
     * @param AttachmentRepository $attachments
     * @return Attachment
     * @throws PermissionDeniedException
     * @throws Exception
     */
    public function handle(Dispatcher $events, AttachmentRepository $attachments)
    {
        $this->events = $events;

        $attachment = $attachments->findOrFail($this->attachmentId, $this->actor);

        $this->assertCan($this->actor, 'delete', $attachment);

        $this->events->dispatch(
            new Deleting($attachment, $this->actor, $this->data)
        );

        if ($attachment->delete()) {
            $attachment->raise(new Deleted($attachment, $this->actor));
        }

        $this->dispatchEventsFor($attachment, $this->actor);

        return $attachment;
    }
}
