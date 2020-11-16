<?php

namespace App\Notifications\Messages\Wechat;

use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 用户注册通知 - 微信
 */
class RegisterWechatMessage extends SimpleMessage
{
    public $tplId = 13;

    protected $actor;

    protected $data;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    public function __construct(SettingsRepository $settings, UrlGenerator $url)
    {
        $this->settings = $settings;
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
        return [
            $this->settings->get('site_name'),
            $this->actor->username,
            Carbon::now()->toDateTimeString(),
//            $this->actor->groups->pluck('name')->join('、'), // 用户组
            $this->url->to(''),
        ];
    }

}
