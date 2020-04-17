<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use Carbon\Carbon;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 内容点赞通知
 *
 * Class LikedMessage
 * @package App\MessageTemplate\Wechat
 */
class LikedMessage extends DatabaseMessage
{
    protected $tplId = 26;

    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        dd('contentReplaceVars-database');
        dd($data);

        return [
            $this->notifiable->username,
            $this->settings->get('site_name'),
            Carbon::now()->toDateTimeString(),
//            $this->notifiable->groups->pluck('name')->join('、'), // 用户组
            $this->url->to(''),
        ];
    }
}
