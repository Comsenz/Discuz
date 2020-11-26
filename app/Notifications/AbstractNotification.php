<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *   http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Notifications;

use App\Models\NotificationTpl;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

abstract class AbstractNotification extends Notification
{
    /**
     * @var Collection
     */
    protected static $tplData;

    protected static function getTemplate($tplId)
    {
        $values = array_values($tplId);
        if (self::$tplData) {
            self::$tplData->whereIn('id', $values);
        }

        self::$tplData = NotificationTpl::query()->whereIn('id', $values)->get();
    }

    protected function getNotificationChannels()
    {
        $channel = [];
        self::$tplData->each(function ($item) use (&$channel) {
            /** @var NotificationTpl $item */
            if ($item->status) {
                array_push($channel, NotificationTpl::enumType($item->type));
            }
        });

        return $channel;
    }

    abstract protected function setTemplate();

    abstract protected function via($notifiable);

    abstract protected function getTplModel($type);
}
