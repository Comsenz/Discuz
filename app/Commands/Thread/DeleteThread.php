<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeleteThread.php xxx 2019-11-08 15:26:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Events\Thread\Deleted;
use App\Events\Thread\Deleting;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the thread to delete.
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
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param int $threadId
     * @param User $actor
     * @param array $data
     */
    public function __construct($threadId, User $actor, array $data = [])
    {
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @return Thread
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId, $this->actor);

        $this->assertCan($this->actor, 'delete', $thread);

        $this->events->dispatch(
            new Deleting($thread, $this->actor, $this->data)
        );

        $thread->raise(new Deleted($thread));
        $thread->forceDelete();

        $this->dispatchEventsFor($thread, $this->actor);

        return $thread;
    }
}
