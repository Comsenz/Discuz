<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;
use App\Models\SessionToken;
use App\Models\UserWechat;
use Discuz\Http\DiscuzResponseFactory;
use EasyWeChat\Kernel\Messages\Text;

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
            if (isset($message['MsgType']) && $message['MsgType'] == 'event') {
                switch ($message->Event) {
                    case 'subscribe':
                    case "SCAN":
                        $this->event($message);
                        break;
                }
            }
        });
        $response  = $this->app->server->serve();
        return  DiscuzResponseFactory::XmlResponse($response->getContent());
    }
    protected function event($message)
    {
        $openid = $message->FromUserName;
        $EventKey = $message->EventKey;
        $wechatuser = UserWechat::where('mp_openid',$openid)->first();
        if($wechatuser){
            //老用户  跟新扫描二维码用户
            SessionToken::where('token',$EventKey)->update([
                    'user_id'=>$wechatuser['user_id'],
                ]);
            $text = new Text();
            $text->content = trans('login.WebUser_login_success');
        }else{
            //新用户,跳转绑定页面
            $user = $this->app->user->get($openid);
            $user_wechats= new UserWechat();
            $user_wechats->openid = $user->mp_openid;
            $user_wechats->nickname =  $user->nickname;
            $user_wechats->sex = $user->sex;
            $user_wechats->province =$user->province;
            $user_wechats->city = $user->city;
            $user_wechats->country = $user->country;
            $user_wechats->headimgurl = $user->headimgurl;

            SessionToken::where('token',$EventKey)->update([
                'scope'=>'wechat',
                'payload'=>['openid'=>$openid]
            ]);
            if($user_wechats->save()){
                $text = new Text();
                $text->content = trans('login.WebNewUser_login_success');
            }
        }
    }
}
