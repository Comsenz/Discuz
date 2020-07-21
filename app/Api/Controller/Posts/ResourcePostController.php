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

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Models\Post;
use App\Repositories\PostRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ResourcePostController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * @var PostRepository
     */
    protected $posts;

    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user.groups',
        'likedUsers',
        'commentPosts',
        'commentPosts.user',
        'commentPosts.user.groups',
        'commentPosts.replyUser',
        'commentPosts.replyUser.groups',
        'commentPosts.mentionUsers',
        'commentPosts.images',
        'images',
        'attachments',
    ];

    /**
     * @param PostRepository $posts
     */
    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $postId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        $post = $this->posts->findOrFail($postId, $actor);

        $this->assertCan($actor, 'view', $post);

        if ($post->is_first || $post->is_comment || $post->thread->deleted_at) {
            throw new ModelNotFoundException;
        }

        if (($postRelationships = $this->getPostRelationships($include)) || in_array('commentPosts', $include)) {
            $this->includePosts($post, $request, $postRelationships);
        }

        return $post;
    }

    /**
     * @param Post $post
     * @param ServerRequestInterface $request
     * @param array $include
     * @throws InvalidParameterException
     */
    private function includePosts(Post $post, ServerRequestInterface $request, array $include)
    {
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $isDeleted = Arr::get($this->extractFilter($request), 'isDeleted');

        $comments = $post->newQuery()
            ->whereVisibleTo($actor)
            ->when($isDeleted, function (Builder $query, $isDeleted) use ($actor) {
                if ($isDeleted == 'yes' && $actor->hasPermission('viewTrashed')) {
                    // 只看回收站帖子
                    $query->whereNotNull('posts.deleted_at');
                } elseif ($isDeleted == 'no') {
                    // 不看回收站帖子
                    $query->whereNull('posts.deleted_at');
                }
            })
            ->where('reply_post_id', $post->id)
            ->where('is_comment', true)
            ->orderBy('created_at')
            ->skip($offset)
            ->take($limit)
            ->with($include)
            ->get()
            ->each(function (Post $comment) use ($post) {
                $comment->replyPost = $post;
            });

        $post->setRelation('commentPosts', $comments);
    }

    /**
     * @param array $include
     * @return array
     */
    private function getPostRelationships(array $include)
    {
        $prefixLength = strlen($prefix = 'commentPosts.');
        $relationships = [];

        foreach ($include as $relationship) {
            if (substr($relationship, 0, $prefixLength) === $prefix) {
                $relationships[] = substr($relationship, $prefixLength);
            }
        }

        return $relationships;
    }
}
