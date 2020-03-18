<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Settings;

use App\Models\Setting;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingRepository;
use Illuminate\Support\Arr;

class SettingsRepository implements ContractsSettingRepository
{
    protected $settings = null;

    public function all()
    {
        $settings = [];
        Setting::all()->each(function ($setting) use (&$settings) {
            $tag = $setting['tag'] ?? 'default';
            $settings[$tag][$setting['key']] = $setting['value'];
        });
        return $this->settings ?? $this->settings = collect($settings);
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

        $settings = $this->all()->toArray();
        Arr::set($settings, $tag.'.'.$key, $value);

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
