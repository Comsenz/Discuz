<?php


namespace App\Traits;


use App\Notifications\DiscuzChannelManager;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

trait Notifiable
{
    use HasDatabaseNotifications,RoutesNotifications;

//    /**
//     * 重载通知
//     * @param $instance
//     */
//    public function notify($instance)
//    {
//        app(DiscuzChannelManager::class)->send($this, $instance);
//    }
//
//    /**
//     * Get the entity's notifications.
//     *
//     * @return MorphMany
//     */
//    public function notifications()
//    {
//        return $this->morphMany(DatabaseNotification::class, 'notifiable');
//    }
//
//    /**
//     * Get the entity's read notifications.
//     *
//     * @return Builder
//     */
//    public function readNotifications()
//    {
//        return $this->notifications()->whereNotNull('read_at');
//    }
//
//    /**
//     * Get the entity's unread notifications.
//     *
//     * @return MorphMany|Builder
//     */
//    public function unreadNotifications()
//    {
//        return $this->notifications()->whereNull('read_at');
//    }
}
