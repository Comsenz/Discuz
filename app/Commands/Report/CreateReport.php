<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Report;

use App\Events\Report\Saving;
use App\Models\Report;
use App\Models\User;
use App\Validators\ReportValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateReport
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
     * The attributes of the new category.
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
     * @param ReportValidator $validator
     * @return Report
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ReportValidator $validator)
    {
        $this->events = $events;
        $data = $this->data;

        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'createCategory');

        $userId = Arr::get($data, 'attributes.user_id');
        $threadId = Arr::get($data, 'attributes.thread_id', 0);
        $postId = Arr::get($data, 'attributes.post_id', 0);
        $reason = Arr::get($data, 'attributes.reason');

        $validator->valid(Arr::get($data, 'attributes'), ['user_id']);

        /**
         * 判断是否存在,合并理由
         */
        $query = Report::query();

        $exists = $query->where([
            'user_id' => $userId,
            'thread_id' => $threadId,
            'post_id' => $postId,
        ])->exists();

        if ($exists) {
            // 合并理由
            $report = $query->first();
            $report->reason = $report->reason .= '、' . $reason;
        } else {
            $report = Report::build(
                $userId,
                $threadId,
                $postId,
                Arr::get($data, 'attributes.type'),
                $reason
            );
        }

        $this->events->dispatch(
            new Saving($report, $this->actor, $this->data)
        );

        $report->save();

        $this->dispatchEventsFor($report);

        return $report;
    }
}
