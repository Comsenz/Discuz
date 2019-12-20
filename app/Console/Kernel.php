<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console;

use Discuz\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            file_put_contents('/tmp/aaa.txt', "asdf\r\n", FILE_APPEND);
        })->everyMinute();

        $schedule->call(function () {
            file_put_contents('/tmp/aaa.txt', "ddddd\r\n", FILE_APPEND);
        })->everyFiveMinutes();
    }
}
