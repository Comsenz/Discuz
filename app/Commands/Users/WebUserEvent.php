<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;
use App\Models\SessionToken;
use App\Models\UserWallet;
use App\Models\UserWechat;
use Discuz\Http\DiscuzResponseFactory;
use EasyWeChat\OfficialAccount\Application;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Support\Arr;

class WebUserEvent
{
    /**
     * 微信参数
     *
     * @var string
     */
    public $settings;
    public $qrcode;

    public function __construct(array $wx_config)
    {
        $this->wx_config = $wx_config;
    }


    /**
     * @return \EasyWeChat\Kernel\Clauses\Clause|\Psr\Http\Message\ResponseInterface
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function handle()
    {
        $app = Factory::officialAccount($this->wx_config);

        if(Arr::get($this->wx_config, 'echostr') && $app->server->validate()) {
            return DiscuzResponseFactory::HtmlResponse('success');
        }

        return $app->server->validate()->push(function ($message) {
            if ($message['MsgType'] == 'event') {
                switch ($message->Event) {
                    case 'subscribe':
                    case "SCAN":
                        $this->event($message);
                        break;
                }
            }
        });
    }
    protected function event($message)
    {
        $openid = $message->FromUserName;
        $EventKey = $message->EventKey;
        $wechat_user = UserWallet::where('mp_openid',$openid)->first();
        if($wechat_user){
            //老用户  跟新扫描二维码用户
            SessionToken::where('token',$EventKey)->update([
                    'user_id'=>$wechat_user['user_id'],
                ]);
            $text = new Text();
            $text->content = trans('login.WebUser_login_success');
        }else{
            //新用户,跳转绑定页面
            $app = Factory::officialAccount($this->wx_config);
            $user = $app->user->get($openid);
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
