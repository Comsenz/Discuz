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
        $events->listen(Saving::class, ChangeSiteMode::class);
        $events->listen(Saving::class, CheckWatermarkSetting::class);

        $events->listen(Saved::class, ClearDisabledPermission::class);
        $events->listen(Saved::class, QcloudSettingReport::class);
    }
}
