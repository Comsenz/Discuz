<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

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
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 检查发帖频率
        // if (! $request->getAttribute('bypassFloodgate')) {
        //     $this->floodgate->assertNotFlooding($actor);
        // }

        return $this->bus->dispatch(
            new CreatePost($threadId, $actor, $data, $ip)
        );
    }
}
