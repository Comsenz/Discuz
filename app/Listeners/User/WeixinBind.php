<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\UserVerify;
use App\Models\UserWechat;

class WeixinBind
{
    public function handle(UserVerify $events)
    {
        if (isset($events->data['openid'])) {
            UserWechat::where('mp_openid', $events->data['openid'])->update(['user_id' => $events->user->id]);
        }
    }
}
