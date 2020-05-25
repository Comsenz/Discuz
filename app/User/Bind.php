<?php


namespace App\User;


use App\Models\SessionToken;
use App\Models\UserWechat;
use App\Repositories\MobileCodeRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        if (in_array($scope, ['wechat', 'wechatweb', 'min'])) {
            $wechat = UserWechat::where($this->platform[$scope], $openid)->first();
            $wechat->user_id = $user->id;
            // 已经存在绑定，抛出异常
            if ($wechat->user_id) {
                throw new \Exception('account_has_been_bound');
            }
            /**
             * 如果用户没有头像，绑定微信时观察者中设置绑定微信用户头像
             * @see UserWechatObserver
             */
            $wechat->save();
        }
    }

    public function mobile($mobile, $user)
    {
        $mobileCode = $this->mobileCode->getSmsCode($mobile, 'bind', 1);

        if ($mobileCode) {
            $user->mobile = $mobile;
            $user->save();
        }
    }
}
