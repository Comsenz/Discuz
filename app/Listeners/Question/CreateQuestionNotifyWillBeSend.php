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

use App\Events\Question\Created;
use App\MessageTemplate\QuestionedMessage;
use App\MessageTemplate\Wechat\WechatQuestionedMessage;
use App\Models\Thread;
use App\Notifications\Questioned;
use Illuminate\Support\Arr;

class CreateQuestionNotifyWillBeSend
{
    public function handle(Created $event)
    {
        $question = $event->question;
        $actor = $event->actor;

        // 向回答人发送问答通知
        $question->beUser->notify(new Questioned($question, $actor, QuestionedMessage::class));
        $question->beUser->notify(new Questioned($question, $actor, WechatQuestionedMessage::class, [
            'message' => $question->thread->getContentByType(Thread::CONTENT_LENGTH, true),
            'raw' => array_merge(Arr::only($question->toArray(), ['thread_id', 'price']), [
                'actor_username' => $question->thread->isAnonymousName(),   // 提问人姓名/匿名
            ]),
        ]));
    }
}
