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
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Routing\UrlGenerator;
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
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'firstPost.likedUsers',
        'lastThreePosts',
        'lastThreePosts.user',
        'rewardedUsers',
    ];

    /**
     * 特殊关联，无法通过 with 预加载
     */
    public $specialInclude = [
        'firstPost.likedUsers',
        'lastThreePosts',
        'lastThreePosts.user',
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
    ];

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'isSticky' => 'desc',
        'updatedAt' => 'desc',
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
        $query = Arr::get($this->extractFilter($request), 'q');
        $sort = $this->extractSort($request);

        // $criteria = new SearchCriteria($actor, $query, $sort);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $load = array_merge($this->extractInclude($request), ['favoriteState']);

        // 查主题
        // $results = $this->searcher->search($criteria, $limit, $offset);
        $threads = $this->search($actor, $query, $sort, $limit, $offset);

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

        $threads = $threads->load(array_diff($load, $this->specialInclude));

        $specialLoad = array_intersect($this->specialInclude, $load);

        // 特殊关联：最新三条回复
        if (in_array('lastThreePosts', $specialLoad)) {
            $threadIds = $threads->pluck('id');

            $allLastThreePosts = Post::from('posts', 'a')
                ->whereRaw('( SELECT count( * ) FROM posts WHERE a.thread_id = thread_id AND a.id < id ) < ?', [3])
                ->whereIn('thread_id', $threadIds)
                ->withTrashed()
                ->when($actor->can('viewTrashed'), function ($query) {
                    $query->whereNull('a.deleted_at');
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            $threads->map(function ($thread) use ($allLastThreePosts) {
                $thread->setRelation('lastThreePosts', $allLastThreePosts->where('thread_id', $thread->id));
            });
        }

        // 特殊关联：喜欢的人
        if (in_array('firstPost.likedUsers', $specialLoad)) {
            $firstPostIds = $threads->pluck('firstPost.id');

            $allLikes = PostUser::from('post_user', 'a')
                ->leftJoin('users', 'a.user_id', '=', 'users.id')
                ->whereRaw('( SELECT count( * ) FROM post_user WHERE a.post_id = post_id AND a.created_at < created_at ) < ?', [10])
                ->whereIn('post_id', $firstPostIds)
                ->orderBy('a.created_at', 'desc')
                ->get();

            $threads->map(function ($thread) use ($allLikes) {
                $thread->firstPost->setRelation('likedUsers', $allLikes->where('post_id', $thread->firstPost->id));
            });
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $specialLoad)) {
            $threadIds = $threads->pluck('id');

            $allRewardedUser = Order::from('orders', 'a')
                ->leftJoin('users', 'a.user_id', '=', 'users.id')
                ->whereRaw('( SELECT count( * ) FROM orders WHERE a.type_id = type_id AND a.created_at < created_at ) < ?', [10])
                ->whereIn('type_id', $threadIds)
                ->where('status', 1)
                ->where('type', 2)
                ->orderBy('a.created_at', 'desc')
                ->get();

            $threads->map(function ($thread) use ($allRewardedUser) {
                $thread->setRelation('rewardedUsers', $allRewardedUser->where('type_id', $thread->id));
            });
        }

        return $threads;
    }

    /**
     * @param $actor
     * @param $queryWord
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    public function search($actor, $queryWord, $sort, $limit = null, $offset = 0)
    {
        $query = $this->threads->query()->select('threads.*')->whereVisibleTo($actor);

        // 关键词搜索
        $query->when($queryWord, function ($query, $queryWord) {
            $query->leftJoin('posts', 'threads.id', '=', 'posts.thread_id')
                ->where('content', 'like', "%{$queryWord}%")
                ->where('is_first', true);
        });

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

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        // 搜索事件，给插件一个修改它的机会。
        // $this->events->dispatch(new Searching($search, $criteria));

        $this->threadCount = $limit > 0 ? $query->count() : null;

        return $query->get();
    }
}
