<?php

namespace App\Observers;

use App\Models\Password;
use Filament\Notifications\Notification;

class PasswordObserver
{
    /**
     * Handle the Password "created" event.
     */
    public function created(Password $password): void
    {
        Notification::make()
            ->title('Password created')
            ->sendToDatabase(
                $password->user,
            );
    }

    /**
     * Handle the Password "updated" event.
     */
    public function updated(Password $password): void
    {
        //
    }

    /**
     * Handle the Password "deleted" event.
     */
    public function deleted(Password $password): void
    {
        //
    }

    /**
     * Handle the Password "restored" event.
     */
    public function restored(Password $password): void
    {
        //
    }

    /**
     * Handle the Password "force deleted" event.
     */
    public function forceDeleted(Password $password): void
    {
        //
    }
}
