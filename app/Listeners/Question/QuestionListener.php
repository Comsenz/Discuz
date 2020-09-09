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

use App\Events\Post\Saved as PostSaved;
use App\Events\Question\Saved as QuestionAnswerSaved;
use Illuminate\Contracts\Events\Dispatcher;

class QuestionListener
{
    public function subscribe(Dispatcher $events)
    {
        // 创建帖子后，发表问答内容
        $events->listen(PostSaved::class, SaveQuestionToDatabase::class);

        /**
         * 回答问题后
         * @see QuestionAnswerMakeMoney 打款
         * @see QuestionAttachment 绑定附件
         */
        $events->listen(QuestionAnswerSaved::class, QuestionAnswerMakeMoney::class); // 打款
    }
}
