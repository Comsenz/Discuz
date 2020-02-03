<?php


namespace App\Listeners\User;


use App\Events\Users\Logind;
use App\Models\Group;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ServerRequestInterface;

class ChangeLastActived
{
    /**
     * @var SettingsRepository
     */
    public $settings;

    public $app;

    /**
     * @param SettingsRepository $settings
     * @param Application $app
     */
    public function __construct(SettingsRepository $settings, Application $app)
    {
        $this->settings = $settings;
        $this->app = $app;
    }

    /**
     * @param Logind $event
     */
    public function handle(Logind $event)
    {
        $user = $event->user;
        $request = $this->app->make(ServerRequestInterface::class);
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR');

        // 检查用户是否加入站点
        if ($this->settings->get('site_mode') == 'pay') {
            // 付费模式开启时间
            $sitePayTime = Carbon::parse($this->settings->get('site_pay_time'));

            // 如果用户到期时间为空，并且用户在站点开启付费模式之前加入站点，为其添加到期时间
            if (! $user->expired_at && $user->joined_at < $sitePayTime) {
                $siteExpire = $this->settings->get('site_expire');

                $user->expired_at = Carbon::now()->addDays($siteExpire);
            }
        }

        // 如果用户没有用户组
        if (! $user->groups->count()) {
            $user->groups()->attach(Group::MEMBER_ID);
        }

        // 更新用户最后登录时间
        $user->login_at = Carbon::now();
        $user->last_login_ip = $ip;

        $user->save();
    }

}
