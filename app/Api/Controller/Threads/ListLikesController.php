<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Models\Order;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListLikesController extends ListThreadsController
{
    use AssertPermissionTrait;

    protected $users;

    public function __construct(ThreadRepository $threads, UrlGenerator $url, UserRepository $users)
    {
        parent::__construct($threads, $url);

        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'viewThreads');

        $limit = $this->extractLimit($request);
        $filter = $this->extractFilter($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

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
        // 加载其他关联
        $threads->loadMissing($include);

        // 处理付费主题内容
        if (in_array('firstPost', $include) || in_array('threadVideo', $include)) {
            $threads = $this->cutThreadContent($threads, $actor, $include);
        }

        return $threads;
    }

    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {

        $user_id = Arr::get($filter, 'user_id', '0');

        $query = $this->threads->query()
            ->select('threads.*')
            ->join('posts', 'threads.id', '=', 'posts.thread_id')
            ->join('post_user', 'posts.id', '=', 'post_user.post_id')
            ->where('post_user.user_id', $user_id)
            ->where('posts.is_first', true)
            ->where('threads.is_approved', Thread::APPROVED)
            ->whereNull('threads.deleted_at');

        $this->threadCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        $query->orderBy('post_user.created_at', 'desc');

        return $query->get();
    }
}
