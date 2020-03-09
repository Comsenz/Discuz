<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Censor\Censor;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateThreadVideo
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * The id of the thread.
     *
     * @var int
     */
    public $threadId;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param $threadId
     * @param array $data
     */
    public function __construct(User $actor, $threadId, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->threadId = $threadId;
    }

    /**
     * @param EventDispatcher $events
     * @param BusDispatcher $bus
     * @param Censor $censor
     * @param Thread $thread
     * @param ThreadVideo $threadVideo
     * @return ThreadVideo
     * @throws PermissionDeniedException
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, Censor $censor, Thread $thread, ThreadVideo $threadVideo)
    {
        $this->events = $events;

        $thread = $thread->findOrFail($this->threadId);

        $this->assertCan($this->actor, 'createThreadVideo');

        $threadVideo->user_id = $this->actor->id;
        $threadVideo->thread_id = $thread->id;
        $threadVideo->file_id   = Arr::get($this->data, 'attributes.file_id');
        $threadVideo->media_url = Arr::get($this->data, 'attributes.media_url');
        $threadVideo->cover_url = Arr::get($this->data, 'attributes.cover_url');

        $threadVideo->save();

        return $threadVideo;
    }
}
