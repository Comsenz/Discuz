<?php


namespace App\Settings;


use App\Models\Setting;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingRepository;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SettingsRepository implements ContractsSettingRepository
{
    protected $cache;

    protected $key = 'settings';

    protected $settings = null;

    public function __construct(Factory $cache)
    {
        $this->cache = $cache;
    }

    public function all()
    {
        $settings = $this->settings ?? $this->cache->get($this->key);

        if(true || !$settings) {
            $settings = [];

            Setting::all()->each(function ($setting) use (&$settings) {
                $tag = $setting['tag'] ?? 'default';
                $settings[$tag][$setting['key']] = $setting['value'];
            });

            $settings = collect($settings);
            $this->cache->put($this->key, $settings);
            $this->settings = $settings;
        }

        return $settings;
    }

    public function get($key, $tag = 'default', $default = null)
    {
        return Arr::get($this->all(), $tag.'.'.$key, $default);
    }

    public function tag($tag = 'default') {
        return Arr::get($this->all(), $tag);
    }

    public function set($key, $value = '', $tag = 'default')
    {
        if(!is_string($value)) {
            return false;
        }
        $settings = $this->all()->toArray();
        Arr::set($settings, $tag.'.'.$key, $value);

        $query = Setting::where([['key', $key], ['tag', $tag]]);

        // 加密
        Setting::setValue($key, $value);

        $method = $query->exists() ? 'update' : 'insert';
        $query->$method(compact('key', 'value', 'tag'));

        return $this->cache->put($this->key, collect($settings));
    }

    public function delete($key, $tag = 'default')
    {
        Setting::where([['key', $key], ['tag', $tag]])->delete();
        $settings = $this->all()->toArray();
        Arr::pull($settings, $tag.'.'.$key);
        return $this->cache->put($this->key, collect($settings));
    }
}
