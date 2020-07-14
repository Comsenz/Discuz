<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
