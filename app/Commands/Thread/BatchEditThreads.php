<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchEditThreads.php xxx 2019-10-21 14:22:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchEditThreads
{
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the threads.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor
     * @param array $data
     */
    public function __construct(User $actor, array $data)
    {
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

        foreach ($this->data as $data) {
            if (isset($data['id'])) {
                $id = $data['id'];
            } else {
                continue;
            }

            $thread = $threads->query()->whereVisibleTo($this->actor)->find($id);

            if ($thread) {
                $thread->timestamps = false;
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            $attributes = Arr::get($data, 'attributes', []);

            if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
                if ($this->actor->can('approve', $thread)) {
                    $thread->is_approved = $attributes['isApproved'];

                    $thread->raise(new ThreadWasApproved(
                        $thread,
                        $this->actor,
                        ['message' => isset($attributes['message']) ? $attributes['message'] : '']
                    ));
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            if (isset($attributes['isSticky'])) {
                if ($this->actor->can('sticky', $thread)) {
                    $thread->is_sticky = $attributes['isSticky'];
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            if (isset($attributes['isEssence'])) {
                if ($this->actor->can('essence', $thread)) {
                    $thread->is_essence = $attributes['isEssence'];
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            if (isset($attributes['isDeleted'])) {
                if ($this->actor->can('hide', $thread)) {
                    $message = isset($attributes['message']) ? $attributes['message'] : '';

                    if ($attributes['isDeleted']) {
                        $thread->hide($this->actor, $message);
                    } else {
                        $thread->restore($this->actor, $message);
                    }
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            try {
                $this->events->dispatch(
                    new Saving($thread, $this->actor, $data)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }

            $thread->save();

            $result['data'][] = $thread;

            try {
                $this->dispatchEventsFor($thread, $this->actor);
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }
        }

        return $result;
    }
}
