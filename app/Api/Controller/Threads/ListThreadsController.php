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

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Order;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Repositories\TopicRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListThreadsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'firstPost',
        'threadVideo',
        'threadAudio',
        'lastPostedUser',
        'category',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user.groups',
        'deletedUser',
        'firstPost.images',
        'firstPost.attachments',
        'firstPost.likedUsers',
        'firstPost.postGoods',
        'lastThreePosts',
        'lastThreePosts.user',
        'lastThreePosts.replyUser',
        'rewardedUsers',
        'paidUsers',
        'lastDeletedLog',
        'topic',
        'question.beUser',
        'question.beUser.groups',
    ];

    public $mustInclude = [
        'favoriteState',
        'firstPost.likeState',
        'question',
        'onlookerState',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortFields = [
        'id',
        'isSticky',
        'postCount',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'isSticky' => 'desc',
        'createdAt' => 'desc',
        'id' => 'desc',
    ];

    /**
     * @var ThreadRepository
     */
    protected $threads;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    protected $threadCount;

    /**
     * @var string
     */
    protected $tablePrefix;

    /**
     * @param ThreadRepository $threads
     * @param UrlGenerator $url
     */
    public function __construct(ThreadRepository $threads, UrlGenerator $url)
    {
        $this->threads = $threads;
        $this->url = $url;
        $this->tablePrefix = config('database.connections.mysql.prefix');
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Collection|mixed
     * @throws InvalidParameterException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        // 获取推荐到站点信息页数据时 不检查权限
        $filter = $this->extractFilter($request);
        if (Arr::get($filter, 'isSite', '') !== 'yes') {
            $this->assertCan($actor, 'viewThreads');
        }

        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $threads = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->threadCount
        );

        $document->setMeta([
            'threadCount' => $this->threadCount,
            'pageCount' => ceil($this->threadCount / $limit),
        ]);

        Thread::setStateUser($actor, $threads);
        Post::setStateUser($actor);

        // 特殊关联：最新三条回复
        if (in_array('lastThreePosts', $include)) {
            $threads = $this->loadLastThreePosts($threads);
        }

        // 特殊关联：点赞的人
        if (in_array('firstPost.likedUsers', $include)) {
            $likedLimit = Arr::get($filter, 'likedLimit', 10);
            $threads = $this->loadLikedUsers($threads, $likedLimit);
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $include)) {
            $rewardedLimit = Arr::get($filter, 'rewardedLimit', 10);
            $threads = $this->loadRewardedUsers($threads, $rewardedLimit, Order::ORDER_TYPE_REWARD);
        }

        // 特殊关联：付费用户
        if (in_array('paidUsers', $include)) {
            $paidLimit = Arr::get($filter, 'paidLimit', 10);
            $threads = $this->loadRewardedUsers($threads, $paidLimit, Order::ORDER_TYPE_THREAD);
        }

        // 特殊关联：最后一次删除的日志
        if (in_array('lastDeletedLog', $include)) {
            $threads->map(function (Thread $thread) {
                $log = $thread->logs()
                    ->with('user')
                    ->where('action', 'hide')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $thread->setRelation('lastDeletedLog', $log);
            });
        }

        // 高亮敏感词
        if (Arr::get($filter, 'highlight') == 'yes') {
            $threads->load('firstPost.stopWords');

            $threads->map(function (Thread $thread) {
                if ($thread->firstPost->stopWords) {
                    $stopWords = explode(',', $thread->firstPost->stopWords->stop_word);
                    $replaceWords = array_map(function ($word) {
                        return '<span class="highlight">' . $word . '</span>';
                    }, $stopWords);

                    $content = str_replace($stopWords, $replaceWords, $thread->firstPost->content);
                    $thread->firstPost->content = $content;
                }
            });
        }

        // 加载其他关联
        $threads->loadMissing($include);

        // 设置对应关系，以解决 N + 1 问题
        if ($relations = array_intersect($include, ['firstPost'])) {
            $threads->map(function ($thread) use ($relations) {
                foreach ($relations as $relation) {
                    if ($thread->$relation) {
                        $thread->$relation->thread = $thread;
                    }
                }
            });
        }

        return $threads;
    }

    /**
     * @param $actor
     * @param $filter
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        /** @var Builder $query */
        $query = $this->threads->query()->select('threads.*')->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        if (Arr::get($filter, 'location')) {
            $this->threadCount = $limit > 0 ? Thread::query()->fromSub($query, 'count')->count() : null;
        } else {
            $this->threadCount = $limit > 0 ? $query->count() : null;
        }

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        // 搜索事件，给插件一个修改它的机会。
        // $this->events->dispatch(new Searching($search, $criteria));

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @param User $actor
     */
    private function applyFilters(Builder $query, array $filter, User $actor)
    {
        // 分类
        if ($categoryId = Arr::get($filter, 'categoryId')) {
            $query->where('threads.category_id', $categoryId);
        }

        // 类型：0普通 1长文 2视频 3图片
        if (($type = Arr::get($filter, 'type', '')) !== '') {
            // 筛选单个类型 或 以逗号分隔的多个类型
            if (strpos($type, ',') === false) {
                $query->where('threads.type', (int) $type);
            } else {
                $type = Str::of($type)->explode(',')->map(function ($item) {
                    return (int) $item;
                })->unique()->values();

                $query->whereIn('threads.type', $type);
            }
        }

        // 作者 ID
        if ($userId = Arr::get($filter, 'userId')) {
            if (is_numeric($type) && $type == Thread::TYPE_OF_QUESTION && Arr::get($filter, 'answer') == 'yes') {
                $query->join('questions', 'threads.id', '=', 'questions.thread_id')
                    ->where(function (Builder $query) use ($userId) {
                        $query->where('threads.user_id', $userId)->orWhere('questions.be_user_id', $userId);
                    });
            } else {
                $query->where('threads.user_id', $userId);
            }

            // 不是本人不能查看该用户的匿名帖
            if ($userId != $actor->id) {
                $query->where('is_anonymous', false);
            }
        }

        // 作者用户名
        if ($username = Arr::get($filter, 'username')) {
            $query->leftJoin('users as users1', 'users1.id', '=', 'threads.user_id')
                ->where(function (Builder $query) use ($username) {
                    $username = explode(',', $username);
                    foreach ($username as $name) {
                        $query->orWhere('users1.username', 'like', "%{$name}%");
                    }
                });

            // 不能查看这些用户的匿名帖
            $query->where('is_anonymous', false);
        }

        // 操作删除者 ID
        if ($deletedUserId = Arr::get($filter, 'deletedUserId')) {
            $query->where('threads.deleted_user_id', $deletedUserId);
        }

        // 操作删除者用户名
        if ($deletedUsername = Arr::get($filter, 'deletedUsername')) {
            $query->leftJoin('users as users2', 'users2.id', '=', 'threads.deleted_user_id')
                ->where('users2.username', 'like', "%{$deletedUsername}%");
        }

        // 发表于（开始时间）
        if ($createdAtBegin = Arr::get($filter, 'createdAtBegin')) {
            $query->where('threads.created_at', '>=', $createdAtBegin);
        }

        // 发表于（结束时间）
        if ($createdAtEnd = Arr::get($filter, 'createdAtEnd')) {
            $query->where('threads.created_at', '<=', $createdAtEnd);
        }

        // 删除于（开始时间）
        if ($deletedAtBegin = Arr::get($filter, 'deletedAtBegin')) {
            $query->where('threads.deleted_at', '>=', $deletedAtBegin);
        }

        // 删除于（结束时间）
        if ($deletedAtEnd = Arr::get($filter, 'deletedAtEnd')) {
            $query->where('threads.deleted_at', '<=', $deletedAtEnd);
        }

        // 浏览次数（大于）
        if ($viewCountGt = Arr::get($filter, 'viewCountGt')) {
            $query->where('threads.view_count', '>=', $viewCountGt);
        }

        // 浏览次数（小于）
        if ($viewCountLt = Arr::get($filter, 'viewCountLt')) {
            $query->where('threads.view_count', '<=', $viewCountLt);
        }

        // 回复数（大于）
        if ($postCountGt = Arr::get($filter, 'postCountGt')) {
            $query->where('threads.post_count', '>=', $postCountGt);
        }

        // 回复数（小于）
        if ($postCountLt = Arr::get($filter, 'postCountLt')) {
            $query->where('threads.post_count', '<=', $postCountLt);
        }

        // 精华帖
        if ($isEssence = Arr::get($filter, 'isEssence')) {
            if ($isEssence == 'yes') {
                $query->where('threads.is_essence', true);
            } elseif ($isEssence == 'no') {
                $query->where('threads.is_essence', false);
            }
        }

        // 置顶帖
        if ($isSticky = Arr::get($filter, 'isSticky')) {
            if ($isSticky == 'yes') {
                $query->where('threads.is_sticky', true);
            } elseif ($isSticky == 'no') {
                $query->where('threads.is_sticky', false);
            }
        }

        // 待审核
        $isApproved = Arr::get($filter, 'isApproved');

        if ($isApproved === '1') {
            $query->where('threads.is_approved', Thread::APPROVED);
        } elseif ($actor->hasPermission('thread.approvePosts')) {
            if ($isApproved === '0') {
                $query->where('threads.is_approved', Thread::UNAPPROVED);
            } elseif ($isApproved === '2') {
                $query->where('threads.is_approved', Thread::IGNORED);
            }
        }

        // 回收站
        if ($isDeleted = Arr::get($filter, 'isDeleted')) {
            if ($isDeleted == 'yes' && $actor->hasPermission('viewTrashed')) {
                // 只看回收站帖子
                $query->whereNotNull('threads.deleted_at');
            } elseif ($isDeleted == 'no') {
                // 不看回收站帖子
                $query->whereNull('threads.deleted_at');
            }
        }

        // TODO: 关键词搜索 优化搜索
        if ($queryWord = Arr::get($filter, 'q')) {
            $query->leftJoin('posts', 'threads.id', '=', 'posts.thread_id')
                ->where('posts.is_first', true)
                ->where(function (Builder $query) use ($queryWord) {
                    $queryWord = explode(',', $queryWord);
                    foreach ($queryWord as $word) {
                        $query->orWhere('threads.title', 'like', "%{$word}%");
                        $query->orWhere('posts.content', 'like', "%{$word}%");
                    }
                });
        }

        // 关注的人的帖子，不包含匿名问答帖
        $fromUserId = Arr::get($filter, 'fromUserId');

        if ($fromUserId && $fromUserId == $actor->id) {
            $query->join('user_follow', 'threads.user_id', '=', 'user_follow.to_user_id')
                ->where('user_follow.from_user_id', $fromUserId)
                ->where('threads.is_anonymous', false);
        }

        // 话题文章
        if ($topicId = Arr::get($filter, 'topicId', '0')) {
            // 更新话题阅读数、主题数
            $topic = app(TopicRepository::class)->findOrFail($topicId);
            $topic->refreshTopicViewCount();
            $topic->refreshTopicThreadCount();

            $query->join('thread_topic', 'threads.id', '=', 'thread_topic.thread_id')
                ->where('thread_topic.topic_id', $topicId);
        }

        // 附近的帖
        $location = explode(',', Arr::get($filter, 'location'), 3);
        $longitude = (float) Arr::get($location, 0, 0);     // 经度
        $latitude = (float) Arr::get($location, 1, 0);      // 纬度
        $distance = abs(Arr::get($location, 2, 5));         // 距离

        if ($longitude && $latitude && $distance) {
            // 距离不能超过 100km
            $distance = $distance > 100 ? 5 : $distance;

            // 地球平均半径 6371km
            $raw = Str::replaceArray('?', [$latitude, $longitude, $latitude], '6371 * acos(
                    cos( radians( ? ) )
                    * cos( radians( `latitude` ) )
                    * cos( radians( `longitude` ) - radians( ? ) )
                    + sin( radians( ? ) )
                    * sin( radians( `latitude` ) )
                )');

            // 地球平均周长 6371km
            $PI = 3.14159265;
            $degree = 40075016 / 360;

            // 经度范围
            $mpdLng = $degree * cos($latitude * ($PI / 180));
            $radiusLng = $distance * 1000 / $mpdLng;
            $minLng = $longitude - $radiusLng;
            $maxLng = $longitude + $radiusLng;

            // 纬度范围
            $radiusLat = $distance * 1000 / $degree;
            $minLat = $latitude - $radiusLat;
            $maxLat = $latitude + $radiusLat;

            $query->selectSub($raw, 'distance')
                ->where('longitude', '<>', 0)
                ->where('latitude', '<>', 0)
                ->whereBetween('longitude', [$minLng, $maxLng])
                ->whereBetween('latitude', [$minLat, $maxLat])
                ->having('distance', '<', $distance)
                ->orderBy('distance');
        }

        // 站点信息页推荐
        if ($isSite = Arr::get($filter, 'isSite')) {
            if ($isSite == 'yes') {
                $query->where('threads.is_site', true);
            } elseif ($isSite == 'no') {
                $query->where('threads.is_site', false);
            }
        }

        // 不展示筛选，默认不传筛选显示的帖子
        if ($isDisplay = Arr::get($filter, 'isDisplay')) {
            if ($isDisplay == 'yes') {
                $query->where('threads.is_display', true);
            } elseif ($isDisplay == 'no') {
                $query->where('threads.is_display', false);
            }
        }

    }

    /**
     * 特殊关联：最新三条回复
     *
     * @param Collection $threads
     * @return Collection
     */
    protected function loadLastThreePosts(Collection $threads)
    {
        $threadIds = $threads->pluck('id');

        $subSql = Post::query()
            ->selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`id` < `id`')
            ->whereRaw($this->tablePrefix . 'a.`thread_id` = `thread_id`')
            ->whereRaw($this->tablePrefix . 'a.`deleted_at` = `deleted_at`')
            ->whereRaw($this->tablePrefix . 'a.`is_first` = `is_first`')
            ->whereRaw($this->tablePrefix . 'a.`is_comment` = `is_comment`')
            ->whereRaw($this->tablePrefix . 'a.`is_approved` = `is_approved`')
            ->toSql();

        $allLastThreePosts = Post::query()
            ->from('posts', 'a')
            ->whereRaw('(' . $subSql . ') < ?', [3])
            ->whereIn('thread_id', $threadIds)
            ->whereNull('deleted_at')
            ->where('is_first', false)
            ->where('is_comment', false)
            ->where('is_approved', Post::APPROVED)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (Post $post) {
                // 截取内容
                $post->content = Str::limit($post->content, 70);

                return $post;
            });

        $threads->map(function (Thread $thread) use ($allLastThreePosts) {
            $thread->setRelation('lastThreePosts', $allLastThreePosts->where('thread_id', $thread->id)->take(3));
        });

        return $threads;
    }

    /**
     * 特殊关联：点赞的人
     *
     * @param Collection $threads
     * @param $limit
     * @return Collection
     */
    protected function loadLikedUsers(Collection $threads, $limit)
    {
        $firstPostIds = $threads->pluck('firstPost.id');

        $subSql = PostUser::query()
            ->selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`post_id` = `post_id`')
            ->whereRaw($this->tablePrefix . 'a.`created_at` < `created_at`')
            ->toSql();

        $allLikes = User::query()
            ->from('post_user', 'a')
            ->leftJoin('users', 'a.user_id', '=', 'users.id')
            ->whereRaw('(' . $subSql . ') < ?', [$limit])
            ->whereIn('post_id', $firstPostIds)
            ->orderBy('a.created_at', 'desc')
            ->get();

        $threads->map(function (Thread $thread) use ($allLikes, $limit) {
            if ($thread->firstPost) {
                $thread->firstPost->setRelation('likedUsers', $allLikes->where('post_id', $thread->firstPost->id)->take($limit));
            }
        });

        return $threads;
    }

    /**
     * 特殊关联：打赏的人
     *
     * @param Collection $threads
     * @param $limit
     * @param int $type
     * @return Collection
     */
    protected function loadRewardedUsers(Collection $threads, $limit, $type)
    {
        switch ($type) {
            case Order::ORDER_TYPE_REWARD:
                $relation = 'rewardedUsers';
                break;
            case Order::ORDER_TYPE_THREAD:
                $relation = 'paidUsers';
                $type = [Order::ORDER_TYPE_THREAD, Order::ORDER_TYPE_ATTACHMENT];
                break;
            default:
                return $threads;
        }

        $threadIds = $threads->pluck('id');

        $subSql = Order::query()
            ->selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`type` = `type`')
            ->whereRaw($this->tablePrefix . 'a.`status` = `status`')
            ->whereRaw($this->tablePrefix . 'a.`thread_id` = `thread_id`')
            ->whereRaw($this->tablePrefix . 'a.`is_anonymous` = `is_anonymous`')
            ->whereRaw($this->tablePrefix . 'a.`created_at` < `created_at`')
            ->toSql();

        $allRewardedUser = User::query()
            ->from('orders', 'a')
            ->join('users', 'a.user_id', '=', 'users.id')
            ->select('a.thread_id', 'users.*')
            ->whereRaw('(' . $subSql . ') < ?', [$limit])
            ->whereIn('a.thread_id', $threadIds)
            ->where('a.status', Order::ORDER_STATUS_PAID)
            ->whereIn('a.type', is_array($type) ? $type : [$type])
            ->where('a.is_anonymous', false)
            ->orderBy('a.created_at', 'desc')
            ->orderBy('a.id', 'desc')
            ->get();

        $threads->map(function (Thread $thread) use ($allRewardedUser, $limit, $relation) {
            $thread->setRelation($relation, $allRewardedUser->where('thread_id', $thread->id)->take($limit));
        });

        return $threads;
    }
}
