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

use App\Events\Post\Revising;
use App\Models\Question;
use App\Models\Thread;
use Exception;
use Illuminate\Support\Arr;

class WhenThePostIsBeingRevised
{
    /**
     * @param Revising $event
     * @throws Exception
     */
    public function handle(Revising $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->thread->type == Thread::TYPE_OF_QUESTION) {
            $attributes = Arr::get($data, 'attributes', []);
            if ($post->is_first) {
                if (isset($attributes['content'])) {
                    // 如果已经回答完成 不允许再次修改内容
                    if ($post->thread->question->is_answer == Question::TYPE_OF_ANSWERED) {
                        throw new Exception(trans('post.post_question_edit_fail_answered'));
                    }
                }
            }
        }
    }

}
