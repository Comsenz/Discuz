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

namespace App\Observer;

use App\Exceptions\TranslatorException;
use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;

class UserObserver
{
    protected $settings;

    protected $app;

    public function __construct(SettingsRepository $settings, Application $app)
    {
        $this->settings = $settings;
        $this->app = $app;
    }

    /**
     * 处理 User「created」事件
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        $this->settings->set('user_count', User::query()->count());
    }

    /**
     * 管理组用户不允许删除
     * @param User $user
     * @throws TranslatorException
     */
    public function deleting(User $user)
    {
        if ($user->isAdmin()) {
            throw new TranslatorException('user_delete_group_error');
        }
    }

    /**
     * @param User $user
     */
    public function deleted(User $user)
    {
        // 删除用户头像
        $rawAvatar = $user->getRawOriginal('avatar');

        if (strpos($rawAvatar, '://') === false) {
            $this->app->make(Factory::class)->disk('avatar')->delete($rawAvatar);
        } else {
            $cosPath = 'public/avatar/' . Str::after($rawAvatar, '://');
            $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
        }
    }
}
