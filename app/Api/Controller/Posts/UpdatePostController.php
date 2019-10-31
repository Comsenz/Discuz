<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UpdatePostController.php xxx 2019-10-24 15:09:00 LiuDongdong $
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
        'editedUser',
        'thread'
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
    public function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);

        return $this->bus->dispatch(
            new EditPost($id, $actor, $data)
        );
    }
}
