<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Commands\Post\EditPost;
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
        'thread'
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['logs'];

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

        return $this->bus->dispatch(
            new EditPost($postId, $actor, $data)
        );
    }
}
