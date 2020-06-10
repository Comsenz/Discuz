<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\CommentPostSerializer;
use App\Api\Serializer\PostSerializer;
use App\Commands\Post\CreatePost;
use App\Models\Post;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreatePostController extends AbstractCreateController
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
        'images',
        'lastThreeComments',
        'lastThreeComments.user',
        'lastThreeComments.replyUser',
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
        $data = $request->getParsedBody()->get('data', []);
        $threadId = Arr::get($data, 'relationships.thread.data.id');
        $ip = ip($request->getServerParams());
        $port = Arr::get($request->getServerParams(), 'REMOTE_PORT');
        $include = $this->extractInclude($request);

        $isComment = (bool) Arr::get($data, 'attributes.isComment');

        if ($isComment) {
            $this->serializer = CommentPostSerializer::class;

            $include = array_merge($include, ['replyUser']);
        }

        $post = $this->bus->dispatch(
            new CreatePost($threadId, $actor, $data, $ip, $port)
        );

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

        return $post->loadMissing($include);
    }
}
