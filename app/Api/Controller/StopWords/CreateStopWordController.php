<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateStopWordController.php xxx 2019-09-26 00:00:00 LiuDongdong $
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Commands\StopWord\BatchCreateStopWord;
use App\Commands\StopWord\CreateStopWord;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateStopWordController extends AbstractCreateController
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
        // TODO: $actor 权限验证 创建敏感词
        // $actor = $request->getAttribute('actor');
        // $this->assertCan($actor, 'createWordList');
        $actor = new \stdClass();
        $actor->id = 1;

        if (strrchr($request->getUri()->getPath(), '/') == '/batch') {
            return $this->bus->dispatch(
                new BatchCreateStopWord($actor, $request->getParsedBody())
            );
        } else {
            return $this->bus->dispatch(
                new CreateStopWord($actor, $request->getParsedBody())
            );
        }

    }
}
