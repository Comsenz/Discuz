<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\UserVerify;
use App\Models\UserWechat;
use Illuminate\Support\Arr;

class WechatBind
{
    public function handle(UserVerify $events)
    {
        if ($openid = Arr::get($events->data, 'openid')) {
            $platform = Arr::get($events->data, 'platform');
            if(in_array($platform, ['mp', 'dev', 'min'])) {
                UserWechat::where($platform.'_openid', $openid)->update(['user_id' => $events->user->id]);
            }
        }
    }
}
