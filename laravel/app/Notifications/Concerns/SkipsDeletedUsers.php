<?php

namespace App\Notifications\Concerns;

use App\Models\User;

trait SkipsDeletedUsers
{
    /**
     * Get the mail channel when the recipient still has an active account.
     *
     * @return array<int, string>
     */
    protected function mailChannelFor(object $notifiable): array
    {
        if ($notifiable instanceof User && $notifiable->trashed()) {
            return [];
        }

        return ['mail'];
    }
}
