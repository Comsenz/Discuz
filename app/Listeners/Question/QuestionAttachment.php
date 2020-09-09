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

namespace App\Listeners\Question;

use App\Events\Question\Saved;
use App\Models\Attachment;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class QuestionAttachment
{
    /**
     * @var BusDispatcher
     */
    protected $bus;

    /**
     * @param BusDispatcher $bus
     */
    public function __construct(BusDispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        // 回答问题后
        $events->listen(Saved::class, [$this, 'whenQuestionAnswerWasSaved']);
    }

    /**
     * 绑定附件
     * @param Saved $event
     */
    public function whenQuestionAnswerWasSaved(Saved $event)
    {
        $question = $event->question;
        $actor = $event->actor;
        $data = $event->data;

        if (!Arr::has($data, 'relationships.attachments.data')) {
            return;
        }

        $attachmentIds = array_column(Arr::get($data, 'relationships.attachments.data'), 'id');

        $query = Attachment::query();
        $query->where('user_id', $actor->id)->where('type_id', 0);
        $query->whereIn('id', $attachmentIds);

        $query->update(['type_id' => $question->id]);
    }
}
