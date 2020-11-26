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

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\Notifications\Messages\Database\RegisterMessage;
use App\Notifications\System;

/**
 * 通知行为 - 系统通知
 *
 * Class Notifications
 * @package App\Listeners\User
 */
class Notifications
{
    public function handle(Registered $event)
    {
        // Tag 发送通知
        $event->user->notify(new System(RegisterMessage::class, $event->user, ['send_type' => 'database']));
    }
}
