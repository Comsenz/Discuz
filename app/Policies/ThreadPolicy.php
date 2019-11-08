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

        if ($actor->hasPermission('thread.viewTrashed')) {
            $this->events->dispatch(
                new ScopeModelVisibility($query, $actor, 'viewTrashed')
            );
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function findTrashed(User $actor, Builder $query)
    {
        $query->withTrashed();
    }
}
