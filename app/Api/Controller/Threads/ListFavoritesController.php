<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListFavoritesController.php xxx 2019-11-12 15:32:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use Discuz\Auth\AssertPermissionTrait;
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

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $load = $this->extractInclude($request);

        $threads = $actor->favoriteThreads()
            ->skip($offset)
            ->take($limit + 1)
            ->orderBy('thread_user.created_at', 'desc')
            ->get();

        $this->hasMoreResults = $limit > 0 && $threads->count() > $limit;

        if ($this->hasMoreResults) {
            $threads->pop();
        }

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->hasMoreResults ? null : 0
        );

        $threads = $threads->load($load);

        return $threads;
    }
}
