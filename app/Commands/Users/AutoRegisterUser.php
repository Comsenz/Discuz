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

namespace App\Commands\Users;

use App\Censor\Censor;
use App\Censor\CensorNotPassedException;
use App\Events\Users\Registered;
use App\Events\Users\Saving;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class AutoRegisterUser
{
    use EventsDispatchTrait;

    public $actor;

    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new user.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param Censor $censor
     * @param SettingsRepository $settings
     * @return User
     */
    public function handle(Dispatcher $events, Censor $censor, SettingsRepository $settings)
    {
        $this->events = $events;
        $request = app('request');

        $this->data['register_ip'] = ip($request->getServerParams());
        $this->data['register_port'] = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);
        //自动注册没有密码，后续用户可以设置密码
        $this->data['password'] = '';

        // 敏感词校验
        try {
            $censor->checkText(Arr::get($this->data, 'username'), 'username');
            $user = User::where('username', Arr::get($this->data, 'username'))->first();
            if ($user) {
                throw new CensorNotPassedException();
            }
        } catch (CensorNotPassedException $e) {
            $this->data['username'] = User::getNewUsername();
        }

        // 审核模式，设置注册为审核状态
        if ($settings->get('register_validate')) {
            $this->data['register_reason'] = $this->data['register_reason'] ?: trans('user.register_by_auto');
            $this->data['status'] = 2;
        }

        // 付费模式，默认注册时即到期
        if ($settings->get('site_mode') == 'pay') {
            $this->data['expired_at'] = Carbon::now();
        }

        $user = User::register(Arr::only($this->data, ['username', 'password', 'register_ip', 'register_port', 'register_reason', 'status']));

        $this->events->dispatch(
            new Saving($user, $this->actor, $this->data)
        );

        $user->save();

        $user->raise(new Registered($user, $this->actor, $this->data));

        $this->dispatchEventsFor($user, $this->actor);

        return $user;
    }
}
