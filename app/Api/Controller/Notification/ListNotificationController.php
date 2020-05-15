<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationSerializer;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
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
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
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
     * @param $filter
     * @param null $limit
     * @param int $offset
     * @return mixed
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

        // type markAsRead
        $actor->unreadNotifications()->where('type', $type)->get()->markAsRead();

        $data = $query->get();

        // 非系统通知
        $list = $data->where('type', '<>', 'system')->pluck('data');

        // 用户 IDs
        $userIds = collect($list)->pluck('user_id')->filter()->unique()->values();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // 主题 ID
        $threadIds = collect($list)->pluck('thread_id')->filter()->unique()->values();
        // 主题及其作者与作者用户组
        $threads = Thread::with('user', 'user.groups')->whereIn('id', $threadIds)->get()->keyBy('id');

        // 获取通知里当前的用户名称和头像
        $data->map(function ($item) use ($users, $threads) {
            if ($item->type != 'system') {
                /**
                 * 解决 N+1 问题
                 */
                $user = $users->get(Arr::get($item->data, 'user_id'));
                if (!empty($user)) {
                    $item->user_name = $user->username;
                    $item->user_avatar = $user->avatar;
                }
                // 查询主题相关内容
                if (Arr::has($item->data, 'thread_username')) {
                    $item->thread_user_name = Arr::get($item->data, 'thread_username', '');
                } elseif (!empty($threadID = Arr::get($item->data, 'thread_id', 0))) {
                    // 获取主题作者用户组
                    if (!empty($threads->get($threadID))) {
                        $threadUser = $threads->get($threadID)->user;
                        $item->thread_user_name = $threadUser->username;
                        $item->thread_user_groups = $threadUser->groups->pluck('name')->join(',');
                    }
                }
            }
        });

        return $data;
    }
}
