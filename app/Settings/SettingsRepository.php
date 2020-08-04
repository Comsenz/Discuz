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

namespace App\Settings;

use App\Models\Setting;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SettingsRepository implements ContractsSettingRepository
{
    /**
     * @var Collection
     */
    protected $settings = null;

    public function __construct()
    {
        $this->settings = new Collection();
    }

    public function all()
    {
        if ($this->settings->isNotEmpty()) {
            return $this->settings;
        }

        $settings = [];
        Setting::all()->each(function ($setting) use (&$settings) {
            $tag = $setting['tag'] ?? 'default';
            $settings[$tag][$setting['key']] = $setting['value'];
        });

        $this->settings = collect($settings);

        return $this->settings;
    }

    public function get($key, $tag = 'default', $default = '')
    {
        return Arr::get($this->all(), $tag . '.' . $key, $default);
    }

    public function tag($tag = 'default')
    {
        return Arr::get($this->all(), $tag);
    }

    public function set($key, $value = '', $tag = 'default')
    {
        if (is_array($value)) {
            return false;
        }

        $this->all();
        $this->settings->put($tag, array_merge((array) $this->tag($tag), [$key => $value]));

        $query = Setting::where([['key', $key], ['tag', $tag]]);

        // 加密
        Setting::setValue($key, $value);

        $method = $query->exists() ? 'update' : 'insert';

        $query->$method(compact('key', 'value', 'tag'));

        return true;
    }

    public function delete($key, $tag = 'default')
    {
        Setting::where([['key', $key], ['tag', $tag]])->delete();
        $settings = $this->all()->toArray();
        return Arr::pull($settings, $tag.'.'.$key);
    }
}
