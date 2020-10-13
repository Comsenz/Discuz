<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

                if ($siteExpire) {
                    $user->expired_at = Carbon::now()->addDays($siteExpire);
                }
            }
        }

        // 如果用户没有用户组
        if (! $user->groups->count()) {
            $user->groups()->attach(Group::MEMBER_ID);
        } else {

        }

        // 更新用户最后登录时间
        $user->login_at = Carbon::now();
        $user->last_login_ip = $ip;
        $user->last_login_port = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);

        $user->save();
    }
}
