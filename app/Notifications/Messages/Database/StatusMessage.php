<?php

namespace App\Notifications\Messages\Database;

use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Support\Arr;

/**
 * 根据用户状态变更 发送不同的通知
 */
class StatusMessage extends SimpleMessage
{
    protected $actor;

    protected $data;

    public function __construct()
    {
        //
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
            $refuse
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
