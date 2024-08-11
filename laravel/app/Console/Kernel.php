<?php

namespace App\Console;

use App\Repositories\MemberMembershipStatusRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Mark members as lapsed.
        $schedule->call(function () {
            $rep = new MemberMembershipStatusRepository();
            $rep->markAsLapsed();
        })->daily();

        // Schedule backups (UTC timezone).
        $schedule->command('backup:clean')->daily()->at('13:00');
        $schedule->command('backup:run')->daily()->at('13:30');

        // Reminders for sub.
        $schedule->call(function () {
            $rep = new MemberMembershipStatusRepository();
            $rep->sendExpiringMembershipReminders(1);
        })->weekly();

        // Reminders for expiring and past due subs.
        $schedule->call(function () {
            $rep = new MemberMembershipStatusRepository();
            $rep->sendExpiringMembershipReminders(3);
            $rep->sendPastDueSubReminders();
        })->monthly();
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
