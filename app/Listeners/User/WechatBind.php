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
        if ($openid = Arr::get($events->data, 'openid') && $platform = Arr::get($events->data, 'platform')) {
            $key = $platform.'_openid';
            if(in_array($key, ['mp', 'dev', 'min'])) {
                UserWechat::where($key, $openid)->update(['user_id' => $events->user->id]);
            }
        }
    }
}
