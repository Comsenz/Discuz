<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditThread.php xxx 2019-10-17 17:44:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Validators\ThreadValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

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
     * @param EventDispatcher $events
     * @param ThreadRepository $threads
     * @param ThreadValidator $validator
     * @return Thread
     * @throws PermissionDeniedException
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(EventDispatcher $events, ThreadRepository $threads, ThreadValidator $validator)
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

        if (isset($attributes['categoryId'])) {
            $this->assertCan($this->actor, 'categorize', $thread);

            $thread->category_id = $attributes['categoryId'];
        }

        if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
            $this->assertCan($this->actor, 'approve', $thread);

            $thread->is_approved = $attributes['isApproved'];

            $thread->raise(new ThreadWasApproved(
                $thread,
                $this->actor,
                ['message' => isset($attributes['message']) ? $attributes['message'] : '']
            ));
        }

        if (isset($attributes['isSticky'])) {
            $this->assertCan($this->actor, 'sticky', $thread);

            $thread->is_sticky = $attributes['isSticky'];
        }

        if (isset($attributes['isEssence'])) {
            $this->assertCan($this->actor, 'essence', $thread);

            $thread->is_essence = $attributes['isEssence'];
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'hide', $thread);

            $message = isset($attributes['message']) ? $attributes['message'] : '';

            if ($attributes['isDeleted']) {
                $thread->hide($this->actor, $message);
            } else {
                $thread->restore($this->actor, $message);
            }
        }

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        $validator->valid($thread->getDirty());

        $thread->save();

        $this->dispatchEventsFor($thread, $this->actor);

        return $thread;
    }
}
