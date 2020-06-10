<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Arr;

/**
 * 根据提现状态变更 发送不同的通知
 *
 * Class WithdrawalMessage
 * @package App\MessageTemplate
 */
class WithdrawalMessage extends DatabaseMessage
{
    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        return $data;
    }
}
