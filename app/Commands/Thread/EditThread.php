<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Events\Category\CategoryRefreshCount;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Events\Users\UserRefreshCount;
use App\MessageTemplate\PostOrderMessage;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\System;
use App\Repositories\ThreadRepository;
use App\Traits\ThreadNoticesTrait;
use App\Validators\ThreadValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;
    use ThreadNoticesTrait;

    /**
     * The ID of the thread to edit.
     *
     * @var int
     */
    public $threadId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the thread.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $threadId The ID of the thread to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the thread.
     */
    public function __construct($threadId, User $actor, array $data)
    {
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param ThreadValidator $validator
     * @return Thread
     * @throws PermissionDeniedException
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, ThreadValidator $validator)
    {
        $this->events = $events;

        $attributes = Arr::get($this->data, 'attributes', []);

        $thread = $threads->findOrFail($this->threadId, $this->actor);

        if (isset($attributes['title'])) {
            $this->assertCan($this->actor, 'rename', $thread);

            $thread->title = $attributes['title'];
        } else {
            // 不修改标题时，不更新修改时间
            $thread->timestamps = false;
        }

        if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
            $this->assertCan($this->actor, 'approve', $thread);
            $thread->is_approved = $attributes['isApproved'];
            $approvedMsg = isset($attributes['message']) ? $attributes['message'] : '';
            // 内容审核通知
            $this->sendIsApproved($thread, ['refuse' => $approvedMsg]);

            $thread->raise(new ThreadWasApproved(
                $thread,
                $this->actor,
                ['message' => $approvedMsg]
            ));
        }

        if (isset($attributes['isSticky'])) {
            $this->assertCan($this->actor, 'sticky', $thread);
            $thread->is_sticky = $attributes['isSticky'];
            // 置顶后 通知发帖人置顶消息
            if ($attributes['isSticky']) {
                $this->sendIsSticky($thread);
            }
        }

        if (isset($attributes['isEssence'])) {
            $this->assertCan($this->actor, 'essence', $thread);
            $thread->is_essence = $attributes['isEssence'];
            // 内容精华通知
            if ($attributes['isEssence']) {
                $this->sendIsEssence($thread);
            }
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'hide', $thread);
            $message = isset($attributes['message']) ? $attributes['message'] : '';

            if ($attributes['isDeleted']) {
                $thread->hide($this->actor, $message);
                // 内容删除通知
                $this->sendIsDeleted($thread, ['refuse' => $message]);
            } else {
                $thread->restore($this->actor, $message);
            }
        }

        // 原分类ID
        $cateId = $thread->category_id;

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        $validator->valid($thread->getDirty());

        $thread->save();

        $this->dispatchEventsFor($thread, $this->actor);

        /**
         * 更改统计数
         */
        $this->events->dispatch(
            new UserRefreshCount($thread->user)
        );
        $this->events->dispatch(
            new CategoryRefreshCount($thread->category, $cateId)
        );

        return $thread;
    }
}
