<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: NotificationController.php xxx 2019-11-06 18:24:00 yanchen $
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationSerializer;
use App\Commands\Notification\ListNotification;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Bus\Dispatcher;
use Discuz\Http\UrlGenerator;

class ListNotificationController extends AbstractListController
{
    use AssertPermissionTrait;

    const TYPE_LIKE = 'App\\Notifications\\Liked';
    const TYPE_REPLIED = 'App\\Notifications\\Replied';
    const TYPE_REWARDED = 'App\\Notifications\\Rewarded';

    private $types = [
        1 => self::TYPE_REPLIED,
        2 => self::TYPE_LIKE,
        3 => self::TYPE_REWARDED,
    ];

    public $notification;

    public $url;

    public $notificationCount;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(NotificationRepository $notification, UrlGenerator $url)
    {

        $this->notification = $notification;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public $serializer = NotificationSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {

        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $actor = User::find(1);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $notifications = $this->search($actor, $filter, $limit, $offset);


        $document->addPaginationLinks(
            $this->url->route('notification.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->notificationCount
        );

        $document->setMeta([
            'threadCount' => $this->notificationCount,
            'pageCount' => ceil($this->notificationCount / $limit),
        ]);

        return $notifications;

    }

    public function search($actor, $filter, $limit = null, $offset = 0)
    {
        $notifications =  $actor->notifications();

        $type = Arr::get($filter, 'type');

        $type && $notifications = $notifications->where('type', Arr::get($this->types , $type))->skip($offset)->take($limit);
        $notifications = $notifications->get();

        $this->notificationCount = $limit > 0 ? ($type ? $actor->notifications()->where('type', Arr::get($this->types , $type))->count() :$actor->notifications()->count() ) : null;

        $actor->unreadNotifications->markAsRead();

        return $notifications;
    }
}
