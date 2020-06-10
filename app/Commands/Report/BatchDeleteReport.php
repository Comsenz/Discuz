<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Report;

use App\Events\Report\Deleting;
use App\Models\Report;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;

class BatchDeleteReport
{
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The ID of the report to delete.
     *
     * @var int
     */
    public $id;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * BatchDeleteReport constructor.
     *
     * @param User $actor
     * @param int $id
     * @param array $data
     */
    public function __construct(User $actor, int $id, array $data = [])
    {
        $this->actor = $actor;
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @return bool
     * @throws Exception
     */
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        $query = Report::query();

        $exists = $query->where('id', $this->id)->exists();

        if ($exists) {
            $report = $query->first();

            $this->events->dispatch(
                new Deleting($report, $this->actor)
            );

            $report->delete();

            $this->dispatchEventsFor($report, $this->actor);
        }

        return $exists;
    }
}
