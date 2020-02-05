<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostModMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle()
    {
        return $this->translator->get('core.post_mod');
    }

    protected function getContent($data)
    {
        /**
         * 判断是否有主题标题
         * 使用首贴内容代替 并过滤 内容的标签
         */
        $thread = Arr::get($data, 'message', false);
        $message = '';
        if ($thread) {
            $message = empty($thread->title) ? $message = $thread->firstPost->content : $thread->title;
        }

        return $this->translator->get('core.post_mod_ok_detail', [
            'user' => $this->notifiable->username,
            'message' => Str::words($message, 10),
            'refuse' => Arr::get($data, 'refuse', ''),
        ]);
    }
}
