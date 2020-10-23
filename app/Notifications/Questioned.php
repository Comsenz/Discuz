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

namespace App\Notifications;

use App\Models\Question;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Bus\Queueable;

/**
 * 问答通知
 *
 * @package App\Notifications
 */
class Questioned extends System
{
    use Queueable;

    public $channel;

    /**
     * @var Question
     */
    public $question;

    /**
     * @var User
     */
    public $user;

    public function __construct(Question $question, User $user, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->question = $question;

        // 提问人 / 被提问人
        $this->user = $user;

        parent::__construct($messageClass, $build);
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $build = [
            'question_id' => $this->question->id,
            'user_id' => $this->user->id,      // 被提问人/提问人
            'thread_id' => $this->question->thread_id,   // 主题ID
            'thread_username' => $this->question->thread->isAnonymousName(), // 必传 主题用户名/匿名用户
            'thread_title' => $this->question->thread->title,
            'content' => '',  // 兼容原数据
            'answer_content' => $this->question->getContentFormat(Question::CONTENT_LENGTH), // 回答的内容
            'amount' => $this->question->price, // 提问价格
            'thread_created_at' => $this->question->thread->formatDate('created_at'),
            'is_answer' => $this->question->is_answer ?? 0, // 是否已回答 (新数据默认0未回答)
            'is_anonymous' => $this->question->thread->is_anonymous, // 是否匿名
        ];

        $this->build($build);

        return $build;
    }

    /**
     * @param $build
     */
    public function build(&$build)
    {
        $content = $this->question->thread->getContentByType(Thread::CONTENT_LENGTH);

        $build['content'] = $content;
    }

    /**
     * 设置频道名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatQuestionedMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\QuestionedMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }
}
