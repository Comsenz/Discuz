<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Models\SessionToken;
use App\Models\UserWechat;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Support\Arr;

class WebUserEvent
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function handle()
    {
        $this->app->server->push(function ($message) {
            switch ($message['Event']) {
                case 'subscribe':
                case 'SCAN':
                    return $this->event($message);
                    break;
            }
        });
    }

    /**
     * @param $message
     * @return Text
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function event($message)
    {
        $openid = $message['FromUserName'];
        $EventKey = $message['EventKey'];
        $wechatuser = UserWechat::where('mp_openid', $openid)->first();

        if ($wechatuser && $wechatuser->user_id) {
            //老用户  跟新扫描二维码用户
            SessionToken::get($EventKey, 'wechat')->update([
                    'user_id'=>$wechatuser->user_id,
                ]);
            return new Text(trans('login.WebUser_login_success'));
        }

        if(is_null($wechatuser)) {
            $wechatuser = new UserWechat();
        }

        //新用户,跳转绑定页面
        $user = $this->app->user->get($openid);

        $wechatuser->mp_openid = Arr::get($user, 'openid');
        $wechatuser->nickname =  Arr::get($user, 'nickname');
        $wechatuser->sex = Arr::get($user, 'sex');
        $wechatuser->province = Arr::get($user, 'province');
        $wechatuser->city = Arr::get($user, 'city');
        $wechatuser->country = Arr::get($user, 'country');
        $wechatuser->headimgurl = Arr::get($user, 'headimgurl');
        $wechatuser->unionid = Arr::get($user, 'unionid');

        SessionToken::get($EventKey, 'wechat')->update([
            'scope'=>'wechat',
            'payload'=> $user
        ]);

        $wechatuser->save();

        return new Text(trans('login.WebNewUser_login_success'));
    }
}
