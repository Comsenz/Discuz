<?php


namespace App\Listeners\User;


use App\Events\Users\UserVerify;
use App\Models\UserWechat;

class WeixinBind
{

    public function handle(UserVerify $events)
    {
        if(isset($events->data['openid'])) {
            UserWechat::where('openid', $events->data['openid'])->update(['user_id' => $events->user->id]);
        }
    }
}
