<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 内容@通知
 *
 * Class RelatedMessage
 * @package App\MessageTemplate
 */
class RelatedMessage extends DatabaseMessage
{
    protected $tplId = 28;

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
