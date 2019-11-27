<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListLikesController.php xxx 2019-11-25 16:57:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListLikesController extends ListPostsController
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

        $query = $actor->likedPosts()
            ->skip($offset)
            ->take($limit)
            ->orderBy('post_user.created_at', 'desc');

        $this->postCount = $limit > 0 ? $query->count() : null;

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->postCount
        );

        $document->setMeta([
            'postCount' => $this->postCount,
            'pageCount' => ceil($this->postCount / $limit),
        ]);

        $posts = $query->get()->load($load);

        return $posts;
    }
}
