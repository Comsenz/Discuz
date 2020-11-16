<?php

namespace App\Notifications\Messages\Wechat;

use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 根据用户状态变更 发送不同的通知 - 微信
 *
 * Class StatusWechatMessage
 * @package App\Notifications\Messages\Wechat
 */
class StatusWechatMessage extends SimpleMessage
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
        $refuse = '无';
        if (Arr::has($data, 'refuse')) {
            if (!empty($data['refuse'])) {
                $refuse = $data['refuse'];
            }
        }

        return [
            $this->actor->username,
            Carbon::now()->toDateTimeString(),
            $this->url->to(''),
            $refuse,
        ];
    }

}
