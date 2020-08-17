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
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 系统Post通知 - 基类
 *
 * Class BasePostMessage
 * @package App\MessageTemplate
 */
class BasePostMessage extends DatabaseMessage
{
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        /**
         * 格式：
         * [
         *     'message' => '标题名'
         *     'refuse' => '拒绝原因'
         *     'raw' => [
         *          'thread_id' => 1
         *          'is_first'  => false
         *     ]
         * ]
         **/
        $message = Arr::get($data, 'message', '');

        return [
            $this->notifiable->username,
            $this->filterSpecialChar ? $this->strWords($message) : $message,
            Arr::get($data, 'refuse', '无')
        ];
    }
}
