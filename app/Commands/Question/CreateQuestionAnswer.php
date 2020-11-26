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

namespace App\Commands\Question;

use App\Censor\Censor;
use App\Censor\CensorNotPassedException;
use App\Events\Question\Saved;
use App\Events\Question\Saving;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateQuestionAnswer
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
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * The current ip address of the actor.
     *
     * @var array
     */
    public $ip;

    /**
     * The current port of the actor.
     *
     * @var int
     */
    public $port;

    /**
     * @param User $actor
     * @param array $data
     * @param string $ip
     * @param string $port
     */
    public function __construct(User $actor, array $data, $ip, $port)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @param EventDispatcher $events
     * @param Censor $censor
     * @param Question $question
     * @return Question
     * @throws CensorNotPassedException
     */
    public function handle(EventDispatcher $events, Censor $censor, Question $question)
    {
        $this->events = $events;

        $questionId = Arr::get($this->data, 'question_id');
        /** @var Question $question */
        $question = $question->query()->find($questionId);

        // check
        $content = $censor->checkText(Arr::get($this->data, 'attributes.content'));
        if (count($censor->wordMod) > 0) {
            throw new CensorNotPassedException('content_banned_show_words', $censor->wordMod);
        }

        $question->content = $content;
        $question->ip = $this->ip;
        $question->port = $this->port;
        $question->is_answer = Question::TYPE_OF_ANSWERED;
        $question->answered_at = Carbon::now();

        $this->events->dispatch(
            new Saving($question, $this->actor, $this->data)
        );

        $question->save();

        // 修改主题展示字段
        // $question->thread->is_display = true;
        // $question->thread->save();

        // 最后触发 Saved
        $question->raise(new Saved($question, $this->actor, $this->data));

        $this->dispatchEventsFor($question, $this->actor);

        return $question;
    }

}
