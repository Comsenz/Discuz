<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\CommentPostSerializer;
use App\Api\Serializer\PostSerializer;
use App\Commands\Post\CreatePost;
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

        $isComment = (bool) Arr::get($data, 'attributes.isComment');

        if ($isComment) {
            $this->serializer = CommentPostSerializer::class;

            $this->include = array_merge($this->include, ['replyUser']);
        }

        return $this->bus->dispatch(
            new CreatePost($threadId, $actor, $data, $ip)
        );
    }
}
