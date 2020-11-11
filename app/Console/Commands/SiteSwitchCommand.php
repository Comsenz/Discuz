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


use App\Models\Setting;
use Discuz\Console\AbstractCommand;

class SiteSwitchCommand extends AbstractCommand
{
    protected $signature = 'site:switch';
    protected $description = '给设置表添加开关属性';

    protected function handle()
    {
        $setting = Setting::query()->where('key', 'site_manage')->get();
        if ($setting->isEmpty()) {
            $setting = new Setting();
            $setting->setRawAttributes([
                'key' => 'site_manage',
                'value' => '[{"key":1,"desc":"PC端","value":true},{"key":2,"desc":"H5端","value":true},{"key":3,"desc":"小程序端","value":true}]',                 // 默认开启
                'tag' => 'default',
            ]);
            echo $setting->save() ? '新增成功' : '新增失败';
            echo PHP_EOL;
        } else {
            echo '不必添加' . PHP_EOL;
        }
    }
}
