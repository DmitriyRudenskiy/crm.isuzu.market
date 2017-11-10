<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckMessage::class,
        CreateFirstUser::class,
        FillEmptyRegions::class,
        GetIncomingCalls::class,
        ParserSipuni::class,
        RemindToCall::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('parser:sipuni')->hourly();
        $schedule->command('remind:call')->hourly();
        $schedule->command('check:message')->everyFiveMinutes();
        $schedule->command('incoming:calls')->everyFiveMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        Artisan::command('inspire', function () {
            $this->comment(Inspiring::quote());
        })->describe('Display an inspiring quote');
    }
}
