<?php

namespace App\Notifications\Messages\Database;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Support\Arr;

/**
 * 用户注册通知
 */
class RegisterMessage extends SimpleMessage
{
    public $tplId = 1;

    protected $actor;

    protected $data;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    public function setData(...$parameters)
    {
        // 解构赋值
        [$firstData, $actor, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->data = $data;

        $this->render();
    }

    protected function titleReplaceVars()
    {
        return [
            '',
            $this->settings->get('site_name'),
        ];
    }

    public function contentReplaceVars($data)
    {
        return [
            $this->actor->username,
            $this->settings->get('site_name'),
            $this->actor->groups->pluck('name')->join('、'),
        ];
    }

    public function render()
    {
        $build = [
            'title' => $this->getTitle(),
            'content' => $this->getContent($this->data),
            'raw' => Arr::get($this->data, 'raw'),
        ];

        Arr::set($build, 'raw.tpl_id', $this->firstData->id);

        return $build;
    }

}
