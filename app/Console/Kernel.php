<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console;

use App\Console\Commands\AvatarCleanCommand;
use App\Console\Commands\FinanceCreate;
use Discuz\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    public $commands = [
        FinanceCreate::class,
        AvatarCleanCommand::class,
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

        // 维护
        $schedule->command('maintain:avatar-clean')->daily();
    }
}
