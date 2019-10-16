<?php


namespace App\Settings;


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
        return $this->settings ?? $this->settings = $this->cache->get($this->key);
    }

    public function get($key, $default = null)
    {
        return $this->all()->get($key, $default);
    }

    public function set($key, $value)
    {
        $settings = $this->all();

        if(! is_null($settings) && $settings instanceof Collection) {
            $settings->put($key, $value);
        } else {
            $settings = collect([$key => $value]);
        }

        return $this->cache->put($this->key, $settings);
    }

    public function delete($keyLike)
    {
        return $this->all()->pull($keyLike);
    }
}
