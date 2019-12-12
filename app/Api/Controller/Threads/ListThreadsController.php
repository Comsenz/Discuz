<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListThreadsController.php xxx 2019-10-09 20:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Order;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListThreadsController extends AbstractListController
{
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
        'lastPostedUser',
        'category',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'firstPost.likedUsers',
        'lastThreePosts',
        'lastThreePosts.user',
        'lastThreePosts.replyUser',
        'rewardedUsers',
    ];

    /**
     * 特殊关联，无法通过 with 预加载
     */
    public $specialInclude = [
        'firstPost.likedUsers',
        'lastThreePosts',
        'lastThreePosts.user',
        'lastThreePosts.replyUser',
        'rewardedUsers',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortFields = [
        'postCount',
        'createdAt',
        'updatedAt',
        'isSticky',
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'isSticky' => 'desc',
        'updatedAt' => 'desc',
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
     * @param ThreadRepository $threads
     * @param UrlGenerator $url
     */
    public function __construct(ThreadRepository $threads, UrlGenerator $url)
    {
        $this->threads = $threads;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $load = array_merge($this->extractInclude($request), ['favoriteState']);

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

        Thread::setStateUser($actor);

        // TODO: load -> loadMissing
        $threads = $threads->load(array_diff($load, $this->specialInclude));

        $specialLoad = array_intersect($this->specialInclude, $load);

        // 特殊关联：最新三条回复
        if (in_array('lastThreePosts', $specialLoad)) {
            $threads = $this->loadLastThreePosts($threads);
        }

        // 特殊关联：喜欢的人
        if (in_array('firstPost.likedUsers', $specialLoad)) {
            $likedLimit = Arr::get($filter, 'likedLimit', 10);
            $threads = $this->loadLikedUsers($threads, $likedLimit);
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $specialLoad)) {
            $rewardedLimit = Arr::get($filter, 'rewardedLimit', 10);
            $threads = $this->loadRewardedUsers($threads, $rewardedLimit);
        }

        // 付费主题，不返回内容
        if (! $actor->isAdmin()) {
            $allRewardedThreads = $actor->orders()
                ->where('status', Order::ORDER_STATUS_PAID)
                ->where('type', Order::ORDER_TYPE_REWARD)
                ->pluck('thread_id');

            $threads->map(function ($thread) use ($allRewardedThreads) {
                if ($thread->price > 0 && $allRewardedThreads->contains($thread->id)) {
                    $thread->firstPost->content = 'TODO: 付费主题无权查看提示语';
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
        $query = $this->threads->query()->select('threads.*')->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        $this->threadCount = $limit > 0 ? $query->count() : null;

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

        // 作者 ID
        if ($userId = Arr::get($filter, 'userId')) {
            $query->where('threads.user_id', $userId);
        }

        // 作者用户名
        if ($username = Arr::get($filter, 'username')) {
            $query->leftJoin('users', 'users.id', '=', 'threads.user_id')
                ->where('users.username', 'like', "%{$username}%");
        }

        // 发表于（开始时间）
        if ($createdAtBegin = Arr::get($filter, 'createdAtBegin')) {
            $query->where('threads.created_at', '>=', $createdAtBegin);
        }

        // 发表于（结束时间）
        if ($createdAtEnd = Arr::get($filter, 'createdAtEnd')) {
            $query->where('threads.created_at', '<=', $createdAtEnd);
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
        } elseif ($actor->can('approvePosts')) {
            if ($isApproved === '0') {
                $query->where('threads.is_approved', Thread::UNAPPROVED);
            } elseif ($isApproved === '2') {
                $query->where('threads.is_approved', Thread::IGNORED);
            }
        }

        // 回收站
        if ($isDeleted = Arr::get($filter, 'isDeleted')) {
            if ($isDeleted == 'yes' && $actor->can('viewTrashed')) {
                // 只看回收站帖子
                $query->whereNotNull('threads.deleted_at');
            } elseif ($isDeleted == 'no') {
                // 不看回收站帖子
                $query->whereNull('threads.deleted_at');
            }
        }

        // 关键词搜索
        if ($queryWord = Arr::get($filter, 'q')) {
            $query->leftJoin('posts', 'threads.id', '=', 'posts.thread_id')
                ->where('posts.content', 'like', "%{$queryWord}%")
                ->where('posts.is_first', true);
        }

        // TODO: 关键词搜索 优化搜索
        // if ($queryWord) {
        //     $subQuery = Post::whereVisibleTo($actor)
        //         ->select('posts.thread_id')
        //         ->where('content', 'like', "%{$queryWord}%")
        //         ->where('is_first', true);
        //
        //     $query->leftJoinSub($subQuery, 'posts', function ($join) {
        //         $join->on('threads.id', '=', 'posts.thread_id');
        //     });
        // }
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

        $allLastThreePosts = Post::from('posts', 'a')
            ->whereRaw('( SELECT count( * ) FROM posts WHERE a.thread_id = thread_id AND a.id < id ) < ?', [3])
            ->whereIn('thread_id', $threadIds)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->take(3);

        $threads->map(function ($thread) use ($allLastThreePosts) {
            $thread->setRelation('lastThreePosts', $allLastThreePosts->where('thread_id', $thread->id));
        });

        return $threads;
    }

    /**
     * 特殊关联：喜欢的人
     *
     * @param Collection $threads
     * @param $limit
     * @return Collection
     */
    protected function loadLikedUsers(Collection $threads, $limit)
    {
        $firstPostIds = $threads->pluck('firstPost.id');

        $allLikes = PostUser::from('post_user', 'a')
            ->leftJoin('users', 'a.user_id', '=', 'users.id')
            ->whereRaw('( SELECT count( * ) FROM post_user WHERE a.post_id = post_id AND a.created_at < created_at ) < ?', [$limit])
            ->whereIn('post_id', $firstPostIds)
            ->orderBy('a.created_at', 'desc')
            ->get()
            ->take($limit);

        $threads->map(function ($thread) use ($allLikes) {
            if ($thread->firstPost) {
                $thread->firstPost->setRelation('likedUsers', $allLikes->where('post_id', $thread->firstPost->id));
            }
        });

        return $threads;
    }

    /**
     * 特殊关联：打赏的人
     *
     * @param Collection $threads
     * @param $limit
     * @return Collection
     */
    protected function loadRewardedUsers(Collection $threads, $limit)
    {
        $threadIds = $threads->pluck('id');

        $allRewardedUser = Order::from('orders', 'a')
            ->leftJoin('users', 'a.user_id', '=', 'users.id')
            ->whereRaw('( SELECT count( * ) FROM orders WHERE a.thread_id = thread_id AND a.created_at < created_at ) < ?', [$limit])
            ->whereIn('thread_id', $threadIds)
            ->where('a.status', Order::ORDER_STATUS_PAID)
            ->where('type', Order::ORDER_TYPE_REWARD)
            ->orderBy('a.created_at', 'desc')
            ->get()
            ->take($limit);

        $threads->map(function ($thread) use ($allRewardedUser) {
            $thread->setRelation('rewardedUsers', $allRewardedUser->where('thread_id', $thread->id));
        });

        return $threads;
    }
}
