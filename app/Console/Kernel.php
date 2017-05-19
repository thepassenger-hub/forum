<?php

namespace App\Console;

use DB;
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
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check if the user ban expired. If it expired enable the user's account.
        $schedule->call(function () {
            DB::table('statuses')
                ->where('status', 'banned')
                ->where('until', '<=' , \Carbon\Carbon::now()->format('Y-m-d H:i:s'))
                ->update(['status' => 'active', 'until' => null]);
        })->twiceDaily(1, 13)->evenInMaintenanceMode();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
