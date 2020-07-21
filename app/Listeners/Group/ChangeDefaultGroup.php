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

namespace App\Listeners\Group;

use App\Events\Group\Saving;
use App\Models\Group;
use Illuminate\Support\Arr;

class ChangeDefaultGroup
{
    public function handle(Saving $event)
    {
        // 设置为默认用户组
        if ((bool) Arr::get($event->data, 'attributes.default', false)) {
            $event->group->default = true;

            $event->group->save();

            Group::query()->where('id', '<>', $event->group->id)->update(['default' => 0]);
        }
    }
}
