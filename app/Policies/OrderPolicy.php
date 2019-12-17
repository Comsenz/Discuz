<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Events\Dispatcher;
use Discuz\Api\Events\ScopeModelVisibility;

class OrderPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Order::class;

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
        if ($actor->cannot('order.viewList')) {
            $query->where('user_id', $actor->id);
            return;
        }
    }
}
