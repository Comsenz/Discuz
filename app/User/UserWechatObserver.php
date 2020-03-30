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

    /**
     * 监听数据即将保存的事件。
     *
     * @param  UserWechat $userWechat
     * @return void
     */
    public function saving(UserWechat $userWechat)
    {
        dd('saving');
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param  UserWechat $userWechat
     * @return void
     */
    public function saved(UserWechat $userWechat)
    {
        dd('saved');
    }
}
