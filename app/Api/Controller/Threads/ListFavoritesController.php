<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Models\Order;
use App\Models\Thread;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListFavoritesController extends ListThreadsController
{
    use AssertPermissionTrait;

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
        $load = $this->extractInclude($request);

        $query = $actor->favoriteThreads();

        $this->threadCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit)->orderBy('thread_user.created_at', 'desc');

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

        $threads = $query->get()->load(array_diff($load, $this->specialInclude));

        $specialLoad = array_intersect($this->specialInclude, $load);

        // 特殊关联：最新三条回复
        if (in_array('lastThreePosts', $specialLoad)) {
            $threads = $this->loadLastThreePosts($threads);
        }

        // 特殊关联：点赞的人
        if (in_array('firstPost.likedUsers', $specialLoad)) {
            $likedLimit = Arr::get($filter, 'likedLimit', 10);
            $threads = $this->loadLikedUsers($threads, $likedLimit);
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $specialLoad)) {
            $rewardedLimit = Arr::get($filter, 'rewardedLimit', 10);
            $threads = $this->loadRewardedUsers($threads, $rewardedLimit);
        }

        // 付费主题对未付费用户只展示部分内容
        if (in_array('firstPost', $load) && ! $actor->isAdmin()) {
            $threads = $this->cutUnpaidThreadContent($threads, $actor);
        }

        return $threads;
    }
}
