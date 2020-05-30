<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Group\PaidGroup;
use App\Events\Users\Logind;
use App\Models\Group;
use App\Models\GroupPaidUser;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
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

    public $events;

    /**
     * @param SettingsRepository $settings
     * @param Application $app
     */
    public function __construct(SettingsRepository $settings, Application $app, Dispatcher $events)
    {
        $this->settings = $settings;
        $this->app = $app;
        $this->events = $events;
    }

    /**
     * @param Logind $event
     */
    public function handle($event)
    {
        $user = $event->user;
        $request = $this->app->make(ServerRequestInterface::class);
        $ip = ip($request->getServerParams());

        // 检查用户是否加入站点
        if ($this->settings->get('site_mode') == 'pay') {
            // 付费模式开启时间
            $sitePayTime = Carbon::parse($this->settings->get('site_pay_time'));

            // 如果用户到期时间为空，并且用户在站点开启付费模式之前加入站点，为其添加到期时间
            if (! $user->expired_at && $user->joined_at < $sitePayTime) {
                $siteExpire = $this->settings->get('site_expire');

                if($siteExpire) {
                    $user->expired_at = Carbon::now()->addDays($siteExpire);
                }
            }
        }

        // 如果用户没有用户组
        if (! $user->groups->count()) {
            $user->groups()->attach(Group::MEMBER_ID);
        } else {
            //检查到期付费用户组
            $groups = $user->groups()->where('is_paid', Group::IS_PAID)->get();

            if ($groups->count()) {
                $now = Carbon::now();
                foreach ($groups as $group => $group_item) {
                    if (empty($group_item->pivot->expiration_time)) {
                        //免费组变为收费组
                        $this->events->dispatch(
                            new PaidGroup($group_item->id, $user)
                        );
                    } elseif ($group_item->pivot->expiration_time < $now) {
                        GroupPaidUser::where('group_id', $group_item->pivot->group_id)
                            ->where('user_id', $group_item->pivot->user_id)
                            ->update(['deleted_at' => $now, 'delete_type' => GroupPaidUser::DELETE_TYPE_EXPIRE]);
                        $user->groups()->detach($group_item);
                    }
                }
            }
        }

        // 更新用户最后登录时间
        $user->login_at = Carbon::now();
        $user->last_login_ip = $ip;

        $user->save();
    }
}
