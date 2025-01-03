<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
//         $schedule->command('scrape:newsapi')->everyMinute();
//         $schedule->command('scrape:newsapiai')->everyFiveMinutes();
//         $schedule->command('scrape:newyorktimes')->everyTenMinutes();
        $schedule->command('scrape:newsapi')->everyMinute();
        $schedule->command('scrape:newsapiai')->everyMinute();
        $schedule->command('scrape:newyorktimes')->everyMinute();
    }
    protected $commands = [
        Commands\ScrapeNews::class,
        Commands\ScrapeNewsApiAI::class,
        Commands\NewYorkTimes::class

    ];
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
