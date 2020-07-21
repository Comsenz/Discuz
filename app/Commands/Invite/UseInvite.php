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

namespace App\Commands\Invite;

use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;

class UseInvite
{
    use EventsDispatchTrait;

    /**
     * 执行操作的id.
     *
     * @var int
     */
    public $inviteId;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 创建站点的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 执行命令
     */
    public function handle()
    {
    }
}
