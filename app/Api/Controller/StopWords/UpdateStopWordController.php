<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UpdateStopWordController.php xxx 2019-09-26 00:00:00 LiuDongdong $
 */

namespace app\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Commands\StopWord\EditStopWord;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateStopWordController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = StopWordSerializer::class;

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
            new EditStopWord($id, $actor, $request->getParsedBody()->all())
        );
    }
}
