<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console;

use App\Console\Commands\AttachmentClearCommand;
use App\Console\Commands\AvatarClearCommand;
use App\Console\Commands\FinanceCreateCommand;
use App\Console\Commands\InviteExpireCommand;
use App\Console\Commands\QueryWechatOrderConmmand;
use Discuz\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    public $commands = [
        FinanceCreateCommand::class,
        AvatarClearCommand::class,
        AttachmentClearCommand::class,
        QueryWechatOrderConmmand::class,
        InviteExpireCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('finance:create')->daily();
        $schedule->command('order:query')->everyMinute()->withoutOverlapping();
        $schedule->command('invite:expire')->everyMinute()->withoutOverlapping();

        // 维护清理
        $schedule->command('clear:attachment')->daily();
        $schedule->command('clear:video')->daily();
    }
}
