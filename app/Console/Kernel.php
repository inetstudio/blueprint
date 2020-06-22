<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('inetstudio:receipts-contest:receipts:winners')->dailyAt('00:30');
        $schedule->command('inetstudio:receipts-contest:receipts:recognize-codes')->everyMinute();
        $schedule->command('inetstudio:receipts-contest:receipts:fns')->hourly();
        $schedule->command('inetstudio:receipts-contest:receipts:moderate')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
