<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
                return $query->whereIn('type', explode(',', $type));
            });
        $query->orderBy('created_at', 'desc');

        $this->notificationCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        // type markAsRead
        $actor->unreadNotifications()->whereIn('type', explode(',', $type))->get()->markAsRead();

        $data = $query->get();

        /**
         * 解决 N+1 问题
         * 获取主题&用户
         */
        $this->getThreads($data, $type, $users, $threads);

        /**
         * 系统通知结构不一
         */
        if ($type != 'system') {
            // 获取通知里当前的用户名称和头像
            $data->map(function ($item) use ($users, $threads, $actor) {
                $user = $users->get(Arr::get($item->data, 'user_id'));
                if (!empty($user)) {
                    $item->user_name = $user->username;
                    $item->user_avatar = $user->avatar;
                    $item->realname = $user->realname;
                }
                // 查询主题相关内容
                if (!empty($threadID = Arr::get($item->data, 'thread_id', 0))) {
                    // 获取主题作者用户组
                    if (!empty($threads->get($threadID))) {
                        $thread = $threads->get($threadID);
                        $item->thread_is_approved = $thread->is_approved;
                        $item->thread_created_at = $thread->formatDate('created_at');
                        $threadUser = $thread->user;
                        if (!empty($threadUser)) {
                            $item->thread_username = $threadUser->username;
                            $item->thread_user_groups = $threadUser->groups->pluck('name')->join(',');
                        }
                    }
                }
            });
        } else {
            // 获取通知里当前的用户名称和头像
            $data->map(function ($item) use ($users, $threads, $actor) {
                if (!empty($threadID = Arr::get($item, 'data.raw.thread_id', 0))) {
                    // 获取主题作者用户组
                    if (!empty($threads->get($threadID))) {
                        $thread = $threads->get($threadID);
                        $item->thread_is_approved = $thread->is_approved;
                        $item->thread_created_at = $thread->formatDate('created_at');
                    }
                }
            });
        }

        return $data;
    }

    /**
     * @param $data
     * @param $type
     * @param $users
     * @param $threads
     */
    protected function getThreads($data, $type, &$users, &$threads)
    {
        if ($type == 'system') {
            $data->where('type', '=', $type);
            $pluck = 'raw.thread_id';
        } else {
            $data->where('type', '<>', $type);
            $pluck = 'thread_id';
        }

        // 非系统通知
        $list = $data->pluck('data');

        // 用户 IDs
        $userIds = collect($list)->pluck('user_id')->filter()->unique()->values();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // 主题 ID
        $threadIds = collect($list)->pluck($pluck)->filter()->unique()->values();
        // 主题及其作者与作者用户组
        $threads = Thread::with('user', 'user.groups')->whereIn('id', $threadIds)->get()->keyBy('id');
    }
}
