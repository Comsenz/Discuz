<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Report;

use App\Events\Report\Saving;
use App\Models\Report;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchEditReport
{
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the report.
     *
     * @var int
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
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        $query = Report::query();

        $report = $query->find(Arr::get($this->data, 'id'));

        if (Arr::has($this->data, 'attributes.status')) {
            $report->status = Arr::get($this->data, 'attributes.status');
        }

        $this->events->dispatch(
            new Saving($report, $this->actor, $this->data)
        );

        $report->save();

        $this->dispatchEventsFor($report, $this->actor);

        return $report;
    }
}
