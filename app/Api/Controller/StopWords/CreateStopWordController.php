<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Commands\StopWord\CreateStopWord;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
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
    public $include = ['user'];

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
        return $this->bus->dispatch(
            new CreateStopWord($request->getAttribute('actor'), $request->getParsedBody()->get('data', []))
        );
    }
}
