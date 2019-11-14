<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UpdateStopWordController.php xxx 2019-09-26 00:00:00 LiuDongdong $
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Commands\StopWord\EditStopWord;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
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
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);

        return $this->bus->dispatch(
            new EditStopWord($id, $actor, $data)
        );
    }
}
