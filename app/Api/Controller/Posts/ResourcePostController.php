<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Exceptions\TranslatorException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Database\Eloquent\Builder;
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
     * @throws TranslatorException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $postId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        $post = $this->posts->findOrFail($postId, $actor);

        $this->assertCan($actor, 'viewPosts', $post);

        if ($post->is_first || $post->is_comment) {
            throw new TranslatorException('post_not_found');
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
