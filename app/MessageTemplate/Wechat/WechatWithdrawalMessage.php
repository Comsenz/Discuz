<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use App\Models\UserWalletCash;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 根据提现状态变更 发送不同的通知 - 微信
 *
 * Class WechatWithdrawalMessage
 * @property UrlGenerator url
 * @package App\MessageTemplate
 */
class WechatWithdrawalMessage extends DatabaseMessage
{
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
        $cash_actual_amount = Arr::get($data, 'cash_actual_amount');
        $created_at = Arr::get($data, 'created_at', '')->toDateTimeString();
        $cashStatus = Arr::get($data, 'cash_status');
        $refuse = Arr::get($data, 'refuse', '');

        $build = [
            $cash_actual_amount,  // 1. 提现金额
            $created_at,          // 2. 提现时间
            UserWalletCash::enumCashStatus($cashStatus), // 3. 提现结果
        ];

        // 4. 原因
        if (!UserWalletCash::notificationByWhich($cashStatus)) {
            array_push($build, $refuse);
        }

        // 5. 跳转地址
        array_push($build, $this->url->to(''));

        return $build;
    }
}
