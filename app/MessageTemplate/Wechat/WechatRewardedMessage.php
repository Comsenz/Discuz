<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use App\Models\Order;
use Carbon\Carbon;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 内容支付通知 - 微信
 * (包含: 打赏帖子/支付付费贴)
 *
 * Class WechatLikedMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRewardedMessage extends DatabaseMessage
{
    protected $tplId = 31;

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
        $message = Arr::get($data, 'message', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $amount = Arr::get($data, 'raw.amount', 0);
        // 获取支付类型
        $orderName = Order::enumType(Arr::get($data, 'raw.type', 0), function ($args) {
            return $args['value'];
        });

        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/details/' . $threadId);
        }

        return [
            $actorName,
            $amount,
            $this->strWords($message),
            $orderName, // 1：注册，2：打赏，3：付费主题，4：付费用户组
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }
}
