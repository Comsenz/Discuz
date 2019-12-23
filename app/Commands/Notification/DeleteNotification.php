<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteNotification
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the notification to delete.
     *
     * @var int
     */
    public $notificationId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param $notificationId
     * @param User $actor
     * @param array $data
     */
    public function __construct($notificationId, User $actor, array $data = [])
    {
        $this->notificationId = $notificationId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param NotificationRepository $notification
     * @return void
     */
    public function handle(Dispatcher $events, NotificationRepository $notification)
    {
        $this->events = $events;

        $notification = $notification->findOrFail($this->notificationId, $this->actor);

        $notification->forceDelete();
    }
}
