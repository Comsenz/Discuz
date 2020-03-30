<?php

namespace App\User;

use App\Models\UserWechat;

class UserWechatObserver
{
    /**
     * 处理 User「created」事件
     *
     * @param UserWechat $userWechat
     * @return void
     */
    public function created(UserWechat $userWechat)
    {
        dd('created');
    }

    /**
     * 处理 User「updated」事件
     *
     * @param UserWechat $userWechat
     * @return void
     */
    public function updated(UserWechat $userWechat)
    {
        dd('updated');
    }
}
