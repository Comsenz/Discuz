<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

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
        return $data;
    }
}
