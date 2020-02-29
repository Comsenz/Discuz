<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationSerializer;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListNotificationController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = NotificationSerializer::class;

    /**
     * @var NotificationRepository
     */
    protected $notifications;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $notificationCount;

    /**
     * @param NotificationRepository $notifications
     * @param UrlGenerator $url
     */
    public function __construct(NotificationRepository $notifications, UrlGenerator $url)
    {
        $this->notifications = $notifications;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

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
            'total' => $this->notificationCount,
            'pageCount' => ceil($this->notificationCount / $limit),
        ]);

        return $notifications;
    }

    /**
     * @param User $actor
     * @param array $filter
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search(User $actor, $filter, $limit = null, $offset = 0)
    {
        $type = Arr::get($filter, 'type');

        $query = $actor->notifications()
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            });
        $query->orderBy('created_at', 'desc');

        $this->notificationCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        //type markAsRead
        $actor->unreadNotifications()->where('type', $type)->get()->markAsRead();

        return $query->get();
    }
}
