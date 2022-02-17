<?php

namespace App\Infrastructure\Kernel;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel;

/**
 * Class ConsoleKernel
 * @package App\Infrastructure\Kernel
 */
class ConsoleKernel extends Kernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/../../Application/Console/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     * @SuppressWarnings("unused")
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cbs:refresh:application-list')->hourly()->withoutOverlapping()->onOneServer();
        $schedule->command('cbs:compute:stats')->hourly()->withoutOverlapping()->onOneServer();
        $schedule->command('cbs:applications:export')->hourly()->withoutOverlapping()->onOneServer();
    }
}
