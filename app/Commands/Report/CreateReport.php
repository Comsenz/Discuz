<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Report;

use App\Events\Report\Saving;
use App\Models\Report;
use App\Models\User;
use App\Validators\ReportValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
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
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ReportValidator $validator)
    {
        $this->events = $events;
        $data = $this->data;

        $this->assertRegistered($this->actor);

        $userId = Arr::get($data, 'attributes.user_id');
        $threadId = Arr::get($data, 'attributes.thread_id', 0);
        $postId = Arr::get($data, 'attributes.post_id', 0);
        $reason = Arr::get($data, 'attributes.reason');

        $validator->valid(Arr::get($data, 'attributes'));

        /**
         * 判断是否存在,合并理由
         */
        $query = Report::query();

        $exists = $query->where([
            'user_id' => $userId,
            'thread_id' => $threadId,
            'post_id' => $postId,
            'status' => 0
        ])->exists();

        if ($exists) {
            /**
             * 合并理由
             *
             * @var Report $report
             */
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
