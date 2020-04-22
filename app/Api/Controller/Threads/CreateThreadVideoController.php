<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadVideoSerializer;
use App\Commands\Thread\CreateThreadVideo;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateThreadVideoController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadVideoSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;


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

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $this->validation->make($attributes, [
            'file_id' => 'required',
        ])->validate();

        return $this->bus->dispatch(
            new CreateThreadVideo($actor, 0, $request->getParsedBody()->get('data', []))
        );
    }
}
