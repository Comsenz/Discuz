<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Events\Thread\Deleted;
use App\Events\Thread\Deleting;
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class BatchDeleteThreads
{
    use EventsDispatchTrait;

    /**
     * The ID array of the threads to delete.
     *
     * @var array
     */
    public $ids;

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
     * @param array $ids
     * @param User $actor
     * @param array $data
     */
    public function __construct(array $ids, User $actor, array $data = [])
    {
        $this->ids = $ids;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @return array
     */
    public function handle(Dispatcher $events, ThreadRepository $threads)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        foreach ($this->ids as $id) {
            $thread = $threads->query()->whereVisibleTo($this->actor)->find($id);

            if (! $thread) {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if ($this->actor->can('forceDelete', $thread)) {
                try {
                    $this->events->dispatch(
                        new Deleting($thread, $this->actor, $this->data)
                    );
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }

                $thread->raise(new Deleted($thread));
                $thread->forceDelete();

                $result['data'][] = $thread;

                try {
                    $this->dispatchEventsFor($thread, $this->actor);
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                continue;
            }
        }

        return $result;
    }
}
