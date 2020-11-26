<?php

namespace App\Notifications\Messages\Database;

use App\Models\Question;
use App\Models\Thread;
use Discuz\Notifications\Messages\SimpleMessage;

class QuestionedMessage extends SimpleMessage
{
    protected $question;

    protected $user;

    public function __construct()
    {
        //
    }

    public function setData(...$parameters)
    {
        // 解构赋值
        [$firstData, $user, $question] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        // 提问人 / 被提问人
        $this->user = $user;
        $this->question = $question;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        return $data;
    }

    public function render()
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

        $this->changeBuild($build);

        return $build;
    }

    /**
     * @param & $build
     */
    public function changeBuild(&$build)
    {
        $content = $this->question->thread->getContentByType(Thread::CONTENT_LENGTH);

        $build['content'] = $content;
    }
}
