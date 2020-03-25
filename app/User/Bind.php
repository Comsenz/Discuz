<?php


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
        if (in_array($scope, ['wechat', 'wechatweb', 'min'])) {
            UserWechat::where($this->platform[$scope], $openid)->update(['user_id' => $user->id]);
        }
    }

    public function mobile($mobile, $user)
    {
        $mobileCode = $this->mobileCode->getSmsCode($mobile, 'bind', 1);

        if ($mobileCode) {
            $user->mobile = $mobile;
            $user->mobile_confirmed = 1;
            $user->save();
        }
    }
}
