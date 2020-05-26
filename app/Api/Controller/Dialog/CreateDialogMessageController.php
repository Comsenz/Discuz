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
use Illuminate\Contracts\Validation\Factory;
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
     * @var Factory
     */
    protected $validation;

    /**
     * @param Dispatcher $bus
     * @param Factory $validation
     */
    public function __construct(Dispatcher $bus, Factory  $validation)
    {
        $this->bus = $bus;
        $this->validation = $validation;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');

        $this->validation->make($attributes, [
            'message_text' => 'required',
        ])->validate();

        return $this->bus->dispatch(
            new CreateDialogMessage($actor, $attributes)
        );
    }
}
