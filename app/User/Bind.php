<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\User;

use App\Models\SessionToken;
use App\Models\UserWechat;
use App\Repositories\MobileCodeRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;

class Bind
{
    protected $app;

    protected $mobileCode;

    protected $platform = [
        'wechat' => 'mp_openid',
        'wechatweb' => 'dev_openid',
    ];

    public function __construct(Application $app, MobileCodeRepository $mobileCode)
    {
        $this->app = $app;
        $this->mobileCode = $mobileCode;
    }

    public function wechat($token, $user)
    {
        $session = SessionToken::get($token);
        $scope = Arr::get($session, 'scope');
        $openid = Arr::get($session, 'payload.openid');
        if (in_array($scope, ['wechat', 'wechatweb'])) {
            $wechatUser = UserWechat::where('user_id', $user->id)->first();
            if (!$wechatUser) {
                $wechat = UserWechat::where($this->platform[$scope], $openid)->first();
            }
            // 已经存在绑定，抛出异常
            if ($wechatUser || !$wechat || $wechat->user_id) {
                throw new \Exception('account_has_been_bound');
            }

            $wechat->user_id = $user->id;
            /**
             * 如果用户没有头像，绑定微信时观察者中设置绑定微信用户头像
             * @see UserWechatObserver
             */
            $wechat->save();
        }
    }
}
