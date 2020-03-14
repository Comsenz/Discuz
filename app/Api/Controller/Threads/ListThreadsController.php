<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

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
        'lastPostedUser',
        'category',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'deletedUser',
        'firstPost.images',
        'firstPost.attachments',
        'firstPost.likedUsers',
        'lastThreePosts',
        'lastThreePosts.user',
        'lastThreePosts.replyUser',
        'rewardedUsers',
        'lastDeletedLog',
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
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'viewThreads');

        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = array_merge($this->extractInclude($request), ['favoriteState']);

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
            $threads = $this->loadRewardedUsers($threads, $rewardedLimit);
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

        // 处理付费主题内容
        if (in_array('firstPost', $include) || in_array('threadVideo', $include)) {
            $threads = $this->cutThreadContent($threads, $actor, $include);
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
            $query->leftJoin('users as users1', 'users1.id', '=', 'threads.user_id')
                ->where(function ($query) use ($username) {
                    $username = explode(',', $username);
                    foreach ($username as $name) {
                        $query->orWhere('users1.username', 'like', "%{$name}%");
                    }
                });
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
                ->where(function ($query) use ($queryWord) {
                    $queryWord = explode(',', $queryWord);
                    foreach ($queryWord as $word) {
                        $query->orWhere('posts.content', 'like', "%{$word}%");
                    }
                });
        }

        //关注的人的文章
        $fromUserId = Arr::get($filter, 'fromUserId');
        if ($fromUserId && $fromUserId == $actor->id) {
            $query->join('user_follow', 'threads.user_id', '=', 'user_follow.to_user_id')
                    ->where('user_follow.from_user_id', $fromUserId);
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

        $subSql = Post::selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`thread_id` = `thread_id`')
            ->whereRaw($this->tablePrefix . 'a.`id` < `id`')
            ->toSql();

        $allLastThreePosts = Post::from('posts', 'a')
            ->whereRaw('(' . $subSql . ') < ?', [3])
            ->whereIn('thread_id', $threadIds)
            ->whereNull('deleted_at')
            ->where('is_approved', Post::APPROVED)
            ->where('is_first', false)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (Post $post) {
                // 引用回复去除引用部分
                if ($post->reply_post_id) {
                    $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
                    $post->content = preg_replace($pattern, '', $post->content);
                }

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

        $subSql = PostUser::selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`post_id` = `post_id`')
            ->whereRaw($this->tablePrefix . 'a.`created_at` < `created_at`')
            ->toSql();

        $allLikes = User::from('post_user', 'a')
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
     * @return Collection
     */
    protected function loadRewardedUsers(Collection $threads, $limit)
    {
        $threadIds = $threads->pluck('id');

        $subSql = Order::selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`type` = `type`')
            ->whereRaw($this->tablePrefix . 'a.`status` = `status`')
            ->whereRaw($this->tablePrefix . 'a.`thread_id` = `thread_id`')
            ->whereRaw($this->tablePrefix . 'a.`created_at` < `created_at`')
            ->toSql();

        $allRewardedUser = User::from('orders', 'a')
            ->join('users', 'a.user_id', '=', 'users.id')
            ->select('a.thread_id', 'users.*')
            ->whereRaw('(' . $subSql . ') < ?', [$limit])
            ->whereIn('a.thread_id', $threadIds)
            ->where('a.status', Order::ORDER_STATUS_PAID)
            ->where('a.type', Order::ORDER_TYPE_REWARD)
            ->orderBy('a.created_at', 'desc')
            ->orderBy('a.id', 'desc')
            ->get();

        $threads->map(function (Thread $thread) use ($allRewardedUser, $limit) {
            $thread->setRelation('rewardedUsers', $allRewardedUser->where('thread_id', $thread->id)->take($limit));
        });

        return $threads;
    }

    /**
     * 付费主题对未付费用户只展示部分内容
     *
     * @param Collection $threads
     * @param User $actor
     * @param array $include
     * @return Collection
     */
    protected function cutThreadContent(Collection $threads, User $actor, array $include)
    {
        // 需付费主题
        $notFreeThreads = $threads->where('price', '>', 0)->pluck('id');

        // 已付费主题
        if ($notFreeThreads && !$actor->isAdmin()) {
            $paidThreads = Order::whereIn('thread_id', $notFreeThreads)
                ->where('user_id', $actor->id)
                ->where('status', Order::ORDER_STATUS_PAID)
                ->where('type', Order::ORDER_TYPE_THREAD)
                ->pluck('thread_id');
        } else {
            $paidThreads = $notFreeThreads;
        }

        // 主题内容处理
        $threads->map(function (Thread $thread) use ($notFreeThreads, $paidThreads, $actor, $include) {
            // 付费主题，是否付费
            if ($notFreeThreads->contains($thread->id)) {
                if ($thread->user_id == $actor->id || $paidThreads->contains($thread->id)) {
                    $thread->setAttribute('paid', true);
                } else {
                    $thread->setAttribute('paid', false);
                }
            }

            // 加载首贴时，处理内容
            if (in_array('firstPost', $include)) {
                // 截取内容、隐藏附件
                if ($thread->price > 0 && $thread->type == 1) {
                    // 列表接口，付费长文主题，无论是否付费，不返回内容
                    $thread->firstPost->content = '';


                    // 未付费时，隐藏图片及附件
                    if (!$thread->getAttribute('paid')) {
                        $thread->firstPost->setRelation('images', collect());
                        $thread->firstPost->setRelation('attachments', collect());
                    }
                } else {
                    $thread->firstPost->content = Str::limit($thread->firstPost->content, Post::SUMMARY_LENGTH);
                }
            }

            // 付费视频，未付费时，隐藏视频
            if (in_array('threadVideo', $include)) {
                if ($thread->price > 0 && $thread->type == 2 && !$thread->getAttribute('paid')) {
                    if ($thread->threadVideo) {
                        $thread->threadVideo->file_id = '';
                        $thread->threadVideo->media_url = '';
                    }
                }
            }
        });

        return $threads;
    }
}
