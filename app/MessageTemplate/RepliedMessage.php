<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 内容回复通知
 *
 * Class RepliedMessage
 * @package App\MessageTemplate
 */
class RepliedMessage extends DatabaseMessage
{
    protected $tplId = 25;

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
