<?php

namespace App\Notifications\Messages\Wechat;

use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class RelatedWechatMessage extends SimpleMessage
{
    protected $post;

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
        [$firstData, $actor, $post, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->post = $post;
        $this->data = $data;

        $this->template();
    }

    public function template()
    {
        $build =  [
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
        $message = strip_tags(Arr::get($data, 'message', ''));   // 去除@样式的html标签
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $replyPostId = Arr::get($data, 'raw.reply_post_id', 0);  // 楼中楼时不为0
        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

        /**
         * TODO 判断是否是楼中楼跳转到楼中楼详情页
         * 主题ID为空时跳转到首页
         */
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $actorName,
            $this->strWords($message),          // @内容
            Carbon::now()->toDateTimeString(),  // 通知时间
            $threadUrl,                         // 跳转地址
        ];
    }

}
