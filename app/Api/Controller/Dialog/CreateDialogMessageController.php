<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogMessageSerializer;
use App\Commands\Dialog\CreateDialogMessage;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateDialogMessageController extends AbstractCreateController
{
    public $serializer = DialogMessageSerializer::class;

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
        $attributes = (int) Arr::get($request->getParsedBody(), 'data.attributes');

        return $this->bus->dispatch(
            new CreateDialogMessage($actor, $attributes)
        );
    }
}
