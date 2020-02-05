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

class PostDeleteMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle()
    {
        return $this->translator->get('core.post_delete');
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

        // 区分语句是否带有 "原因"
        $refuse = Arr::get($data, 'refuse', '');
        if (empty($refuse)) {
            $trans = 'post_mod_no_detail';
        } else {
            $trans = 'post_mod_ok_detail';
        }
        return $this->translator->get('core.' . $trans, [
            'user' => $this->notifiable->username,
            'message' => Str::words($message, 10),
            'refuse' => Arr::get($data, 'refuse', ''),
        ]);
    }
}
