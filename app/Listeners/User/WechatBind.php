<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\UserVerify;
use App\Models\SessionToken;
use App\Models\UserWechat;
use Illuminate\Support\Arr;

class WechatBind
{
    protected $platform = [
        'wechat' => 'mp_openid',
        'wechatweb' => 'dev_openid',
    ];

    public function handle(UserVerify $events)
    {
        if ($token = Arr::get($events->data, 'token')) {
            $session = SessionToken::get($token);
            $scope = Arr::get($session, 'scope');
            $openid = Arr::get($session, 'payload.openid');
            if (in_array($scope, ['wechat', 'wechatweb', 'min'])) {
                UserWechat::where($this->platform[$scope], $openid)->update(['user_id' => $events->user->id]);
            }
        }
    }
}
