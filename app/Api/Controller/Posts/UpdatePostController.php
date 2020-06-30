<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\CommentPostSerializer;
use App\Api\Serializer\PostSerializer;
use App\Commands\Post\EditPost;
use App\Models\Post;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdatePostController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'thread',
        'lastThreeComments',
        'lastThreeComments.user',
        'lastThreeComments.replyUser',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'images',
        'logs',
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $postId = Arr::get($request->getQueryParams(), 'id');
        $data = $request->getParsedBody()->get('data', []);

        /** @var Post $post */
        $post = $this->bus->dispatch(
            new EditPost($postId, $actor, $data)
        );

        if ($post->is_comment) {
            $this->serializer = CommentPostSerializer::class;
        }

        // 被回复帖子的最后三条回复
        $post->setRelation(
            'lastThreeComments',
            Post::query()
                ->where('reply_post_id', $post->reply_post_id)
                ->whereNull('deleted_at')
                ->where('is_first', false)
                ->where('is_comment', true)
                ->where('is_approved', Post::APPROVED)
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get()
        );

        return $post->loadMissing($this->extractInclude($request));
    }
}
