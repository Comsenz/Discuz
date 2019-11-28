<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadPolicy.php xxx 2019-10-30 19:55:00 LiuDongdong $
 */

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Discuz\Api\Events\ScopeModelVisibility;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;

class ThreadPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Thread::class;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if ($actor->cannot('viewThreads')) {
            $query->whereRaw('FALSE');

            return;
        }

        // 回收站
        if (! $actor->hasPermission('threads.viewTrashed')) {
            $query->where(function ($query) use ($actor) {
                $query->whereNull('threads.hidden_at')
                    ->orWhere('threads.user_id', $actor->id)
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($query, $actor, 'hide')
                        );
                    });
            });
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function hide(User $actor, Thread $thread)
    {
        if ($thread->user_id == $actor->id && $actor->can('reply', $thread)) {
            return true;
        }
    }
}
