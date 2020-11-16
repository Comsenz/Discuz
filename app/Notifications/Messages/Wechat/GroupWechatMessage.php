<?php

namespace App\Notifications\Messages\Wechat;

use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 用户角色调整通知 - 微信
 */
class GroupWechatMessage extends SimpleMessage
{
    public $tplId = 24;

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
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        // 跳转到首页
        $redirectUrl = $this->url->to('');

        return [
            $this->actor->username,
            $oldGroup->pluck('name')->join('、'),
            $newGroup->pluck('name')->join('、'),
            $redirectUrl,
        ];
    }

}
