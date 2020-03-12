<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Repositories\ThreadVideoRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Qcloud\QcloudTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class DeleteThreadVideo
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;
    use QcloudTrait;

    public $actor;

    public $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @param EventDispatcher $events
     * @param ThreadVideoRepository $threadVideos
     * @throws Exception
     */
    public function handle(EventDispatcher $events, ThreadVideoRepository $threadVideos)
    {
        $this->events = $events;
        if ($this->thread->type == 2) {
            $threadVideo = $threadVideos->query()->where('thread_id', $this->thread->id)->first();
            if ($threadVideo) {
                $threadVideo->delete();
                $this->deleteVodMedia($threadVideo->file_id);
            }
        }
    }
}
