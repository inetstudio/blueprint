<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inetstudio:social-contest:posts:instagram')->twiceDaily(7, 19);
        //$schedule->command('inetstudio:social-contest:posts:vkontakte')->twiceDaily(8, 20);
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
