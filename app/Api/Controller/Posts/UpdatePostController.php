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
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdatePostController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: User $actor 用户模型
        // $actor = $request->getAttribute('actor');
        $actor = new \stdClass();
        $actor->id = 1;

        $id = Arr::get($request->getQueryParams(), 'id');

        return $this->bus->dispatch(
            new EditPost($id, $actor, $request->getParsedBody())
        );
    }
}
