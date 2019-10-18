<?php


namespace App\Settings;


use App\Models\Setting;
use Discuz\Contracts\Setting\SettingRepository as ContractsSettingRepository;
use Illuminate\Contracts\Cache\Factory;
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

        if(!$settings) {
            $settings = Setting::query()->pluck('value', 'key')->all();
            $this->cache->put($this->key, collect($settings));
        }

        $this->settings = $settings;

        return $this->settings;
    }

    public function get($key, $default = null)
    {
        $value = $this->all()->get($key, $default);
         if(is_null($value)) {
             $value = Setting::query()->where('key', $key)->value('value');
             $value = $value ?? $default;
             $this->set($key, $value);
         }
         return $value;
    }

    public function set($key, $value)
    {
        $settings = $this->all();

        if(! is_null($settings) && $settings instanceof Collection) {
            $settings->put($key, $value);
        } else {
            $settings = collect([$key => $value]);
        }

        Setting::updateOrCreate(['key' => $key], ['value' => $value]);

        return $this->cache->put($this->key, $settings);
    }

    public function delete($keyLike)
    {
        return $this->all()->pull($keyLike);
    }
}
