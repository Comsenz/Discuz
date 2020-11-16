<?php

namespace App\Notifications\Messages\Wechat;

use App\Models\Question;
use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 过期通知 - 微信
 *
 * @package App\Notifications\Messages\Wechat
 */
class ExpiredWechatMessage extends SimpleMessage
{
    public $tplId = 44;

    protected $question;

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
        [$firstData, $actor, $question, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->question = $question;
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
        $name = '';
        $detail = '';
        $content = Arr::get($data, 'content', ''); // 主题内容

        // 问答过期通知模型数据
        if (Arr::get($data, 'raw.model') instanceof Question) {
            /** @var Question $question */
            $question = Arr::get($data, 'raw.model');
            $name = '您的问题超时未收到回答';
            $detail = '返还金额' . $question->price;    // 解冻金额
        }

        // 通知时间
        $dateLine = Carbon::now()->toDateTimeString();

        // 主题ID为空时跳转到首页
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $name,          // {username}       谁
            $detail,        // {detail}         xx已过期
            $content,       // {content}        内容
            $dateLine,      // {dateline}       通知时间
            $threadUrl,     // {redirecturl}    跳转地址
        ];
    }

}
