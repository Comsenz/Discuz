<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Setting;

use App\Events\Setting\Saving;
use App\Events\Setting\Saved;
use Illuminate\Contracts\Events\Dispatcher;

class SettingListener
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, CheckWatermarkSetting::class);
        $events->listen(Saved::class, QcloudSettingReport::class);
    }
}
