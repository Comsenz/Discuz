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
use App\Events\Post\Saved as PostSaved;
use App\Events\Question\Created;
use App\Events\Question\Saved as QuestionAnswerSaved;
use Illuminate\Contracts\Events\Dispatcher;

class QuestionListener
{
    public function subscribe(Dispatcher $events)
    {
        /**
         * 创建帖子后，发表问答内容
         *
         * @see SaveQuestionToDatabase 创建问答信息
         * @see CreateQuestionNotifyWillBeSend 触发回答人通知
         */
        $events->listen(PostSaved::class, SaveQuestionToDatabase::class);
        $events->listen(Created::class, CreateQuestionNotifyWillBeSend::class);

        /**
         * 当帖子修改时
         *
         * @see WhenThePostIsBeingRevised 不允许修改已回答后的内容
         */
        $events->listen(Revising::class, WhenThePostIsBeingRevised::class);

        /**
         * 回答问题后
         *
         * @see QuestionAnswerMakeMoney 打款->钱包扣款->记录钱包日志->钱包变动通知
         * @see ChangeUserWallet 钱包扣款
         * @see SaveWalletLogToDatabase 记录钱包日志
         * @see SendNotifyOfWalletChanges 钱包变动发送通知
         * @see QuestionAttachment 绑定附件
         * @see SendNotifyOfAnswer 回答后发送回执通知
         */
        $events->listen(QuestionAnswerSaved::class, QuestionAnswerMakeMoney::class);
        $events->listen(QuestionAnswerSaved::class, SendNotifyOfAnswer::class);
    }
}
