<?php

namespace App\Notifications\Messages\Wechat;

use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class QuestionedAnswerWechatMessage extends SimpleMessage
{
    public $tplId = 42;

    protected $question;

    protected $user;

    protected $data;

    /**
     * @var UrlGenerator
     */
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function setData(...$parameters)
    {
        [$firstData, $user, $question, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        // 提问人 / 被提问人
        $this->user = $user;
        $this->question = $question;
        $this->data = $data;

        $this->template();
    }

    public function template()
    {
        $build = [
            'title' => $this->getTitle(),
            'content' => $this->getContent($this->data),
            'raw' => Arr::get($this->data, 'raw'),
        ];

        Arr::set($build, 'raw.tpl_id', $this->firstData->id);

        return $build;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        $message = Arr::get($data, 'message', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $actorName = Arr::get($data, 'raw.actor_username', ''); // 回答人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $actorName,
            $this->strWords($message),
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }

}
