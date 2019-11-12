<?php

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
//        $schedule->call(function() {
//            dump(1);
//        })->everyMinute();
//
//        $schedule->call(function() {
//            dump(5);
//        })->everyFiveMinutes();
    }
}
