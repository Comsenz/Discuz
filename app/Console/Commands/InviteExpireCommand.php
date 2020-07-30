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

namespace App\Console\Commands;

use App\Models\Invite;
use App\Repositories\InviteRepository;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;

class InviteExpireCommand extends AbstractCommand
{
    protected $signature = 'invite:expire';

    protected $description = '未使用邀请码过期';

    protected $app;

    protected $invites;

    /**
     * AvatarCleanCommand constructor.
     * @param Application $app
     * @param InviteRepository $invites
     */
    public function __construct(Application $app, InviteRepository $invites)
    {
        parent::__construct();

        $this->app = $app;
        $this->invites = $invites;
    }

    public function handle()
    {
        //清理前天的未发布主题视频数据
        $invitesList = $this->invites->query()
            ->where('status', Invite::STATUS_UNUSED)
            ->where('endtime', '<', Carbon::Now()->timestamp)
            ->get();

        foreach ($invitesList as $invite) {
            $invite->status = Invite::STATUS_EXPIRED;
            $invite->save();
        }

        $this->info('未使用邀请码过期数量：'. $invitesList->count());
    }
}
