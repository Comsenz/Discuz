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

namespace App\Console\Commands;

use App\Models\User;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;

class AvatarClearCommand extends AbstractCommand
{
    protected $signature = 'clear:avatar';

    protected $description = '清理本地/COS未使用的头像';

    protected $app;

    protected $user;

    /**
     * AvatarCleanCommand constructor.
     * @param string|null $name
     * @param Application $app
     * @param User $user
     */
    public function __construct(string $name = null, Application $app, User $user)
    {
        parent::__construct($name);

        $this->app = $app;
        $this->user = $user;
    }

    public function handle()
    {
        // test data
        // $array = [130, 344, 343, 342];
        // $users = $this->user->hasAvatar()->whereIn('id', $array)->get();

        $users = $this->user->hasAvatar()->get();

        $bar = $this->createProgressBar(count($users));

        $bar->start();

        $users->map(function ($user) use ($bar) {
            $img = Str::after($user->getRawOriginal('avatar'), '://');

            $nowAvatar = $user->getRawOriginal('avatar');

            // 判断是否是 Cos 地址（如果是 Cos 就删除本地文件，否则删除 Local 文件）
            if (strpos($nowAvatar, '://') === false) {
                $cosPath = 'public/avatar/' . $img;
                $res = $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
                $type = 'cos';
            } else {
                $res = $this->app->make(Factory::class)->disk('avatar')->delete($img);
                $type = 'local';
            }

            // 删除后输出
            if ($res) {
                $info = '当前值: ' . $nowAvatar;
                $this->question($info);

                $msg = '删除了' . $type . ': ' . $img;
                $this->comment($msg);
            }

            $bar->advance();
        });

        $bar->finish();
    }
}
