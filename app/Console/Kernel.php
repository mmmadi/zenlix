<?php

namespace zenlix\Console;

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
        \zenlix\Console\Commands\FetchEmails::class,
        \zenlix\Console\Commands\TicketProcessing::class,
        \zenlix\Console\Commands\ZenlixUpdate::class,
        \zenlix\Console\Commands\ZenlixLicense::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        /**
         * fetch new mail every 1 minute
         */

        $schedule->command('emails:fetch')
            ->everyMinute();

/**
 * Process tickets every 10 minutes
 */
        $schedule->command('ticket:process')
            ->everyTenMinutes();

/**
 * Get zenlix license every day
 */
        $schedule->command('zenlix:license')
            ->daily();

        //command to remove empty helpImage files
    }
}
