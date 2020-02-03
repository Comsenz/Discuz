<?php


namespace App\Traits;


use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

trait Notifiable
{
    use HasDatabaseNotifications,RoutesNotifications;

    /**
     * Get the entity's notifications.
     *
     * @return MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }
}
