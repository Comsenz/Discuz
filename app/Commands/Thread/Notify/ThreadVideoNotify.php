<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread\Notify;

use App\Settings\SettingsRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Discuz\Foundation\EventsDispatchTrait;

class ThreadVideoNotify
{
    use EventsDispatchTrait;

    /**
     * @var array
     */
    public $config;

    /**
     * 初始化命令参数
     */
    public function __construct()
    {
    }

    /**
     * 执行命令
     * @param SettingsRepository $setting
     * @param ConnectionInterface $connection
     * @param Dispatcher $events
     * @return string
     */
    public function handle(SettingsRepository $setting, ConnectionInterface $connection, Dispatcher $events)
    {
        $this->config = $setting->tag('qcloud');
        //EditMediaComplete
        return 'success';
    }

}
