<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('inetstudio:checks-contest:checks:winners')->dailyAt('00:30');
        $schedule->command('inetstudio:checks-contest:checks:recognize-codes')->everyMinute();
        $schedule->command('inetstudio:checks-contest:checks:fns')->hourly();
        $schedule->command('inetstudio:checks-contest:checks:moderate')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
