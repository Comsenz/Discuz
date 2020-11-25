<?php

namespace App\Notifications\Messages\Database;

use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Support\Arr;

/**
 * Post 通知
 */
class PostMessage extends SimpleMessage
{
    const NOTIFY_EDIT_CONTENT_TYPE = 'edit_content';    // 修改内容
    const NOTIFY_UNAPPROVED_TYPE = 'unapproved';        // 内容不合法/内容忽略
    const NOTIFY_APPROVED_TYPE = 'approved';            // 内容合法
    const NOTIFY_ESSENCE_TYPE = 'essence';              // 内容加精
    const NOTIFY_STICKY_TYPE = 'sticky';                // 内容置顶
    const NOTIFY_DELETE_TYPE = 'delete';                // 内容删除

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
        $message = Arr::get($data, 'message', '');

        return [
            $this->actor->username,
            $this->filterSpecialChar ? $this->strWords($message) : $message,
            Arr::get($data, 'refuse', '无'),
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
