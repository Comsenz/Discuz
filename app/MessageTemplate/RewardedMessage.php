<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 内容支付通知
 * (包含: 打赏帖子/支付付费贴)
 *
 * Class RewardedMessage
 * @package App\MessageTemplate\Wechat
 */
class RewardedMessage extends DatabaseMessage
{
    protected $tplId = 27;

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
