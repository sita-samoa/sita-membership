<?php

namespace App\Console;

use App\Http\Repositories\MemberRepository;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $rep = new MemberRepository();
            // Get profiles that will expire in 3 months or less
            $members = $rep->getMembersExpiringIn(new Carbon("3 months"));

            // @todo - Add to dash board list of members that will expire in 3
            //  months or less.
            // @todo - Add a button to send bulk remindrs to those on the list
            // @todo - Perform bulk operations on the member list (e.g. send reminder)
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
