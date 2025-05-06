<?php

namespace App\Repositories;

use App\Models\User;
use App\Notifications\UserVerificationReminder;
use Carbon\Carbon;

class UserRepository extends Repository
{
    // Method to get users who haven't verified in the last 2 days
    public function getUnverifiedForTwoDays()
    {
        $now = Carbon::now();

        // Fetch users who have not verified their email and are more than 2 days old
        return User::whereNull('email_verified_at')
            ->where('created_at', $now->subDays(2))  // Users created more than 2 days ago
            ->get();
    }

    // Method to get users who haven't verified for more than 2 days
    public function getUnverifiedMoreThanTwoDays()
    {
        $now = Carbon::now();

        // Fetch users who have not verified their email for more than 2 days
        return User::whereNull('email_verified_at')
            ->where('created_at', '<=', $now->subDays(7))  // Created more than 2 days ago
            ->get();
    }

    public function notifyOfUnverifiedAccount($unverifiedUsers)
    {
        // Send the notification to each unverified user
        foreach ($unverifiedUsers as $user) {
            // Send the notification
            $user->notify(new UserVerificationReminder($user));
        }
    }
}
