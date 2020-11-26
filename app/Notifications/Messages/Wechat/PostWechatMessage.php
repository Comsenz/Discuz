<?php

namespace App\Notifications\Messages\Wechat;

use App\Notifications\Messages\Database\PostMessage;
use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * Post 通知 - 微信
 */
class PostWechatMessage extends SimpleMessage
{
    protected $actor;

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
        [$firstData, $actor, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;
        $this->actor = $actor;
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

        // 判断如果是删除通知，帖子被删除后无法跳转到详情页，threadId 清空跳主页
        if ($data['notify_type'] == PostMessage::NOTIFY_DELETE_TYPE) {
            $threadId = 0;
        }

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $this->strWords($message),
            Carbon::now()->toDateTimeString(),
            $threadUrl,
            Arr::get($data, 'refuse', '无'),
        ];
    }

}
