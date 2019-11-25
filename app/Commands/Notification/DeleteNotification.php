<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeleteNotification.php xxx 2019-11-11 11:37:00 yanchen $
 */

namespace App\Commands\Notification;

use App\Events\Post\Deleted;
use App\Events\Post\Deleting;
use App\Models\Post;
use App\Models\User;
use App\Repositories\NotificationRepository;
use App\Repositories\PostRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
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
     * @param int $postId
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
     * @param PostRepository $posts
     * @return Post
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events, NotificationRepository $notification)
    {
        $this->events = $events;

        $notification = $notification->findOrFail($this->notificationId, $this->actor );

        $notification->forceDelete();
    }
}
