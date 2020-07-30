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

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Arr;

/**
 * 根据用户状态变更 发送不同的通知
 *
 * Class StatusMessage
 * @package App\MessageTemplate
 */
class StatusMessage extends DatabaseMessage
{
    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        $refuse = '无';
        if (Arr::has($data, 'refuse')) {
            if (!empty($data['refuse'])) {
                $refuse = $data['refuse'];
            }
        }

        return [
            $this->notifiable->username,
            $refuse
        ];
    }
}
