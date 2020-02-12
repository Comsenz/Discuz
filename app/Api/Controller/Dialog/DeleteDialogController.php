<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Dialog\DeleteDialog;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteDialogController extends AbstractDeleteController
{
    public $serializer = InfoSerializer::class;
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
    public function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $id = (int) Arr::get($request->getParsedBody(), 'data.attributes.id', 0);

        $data = collect();
        $data->push($this->bus->dispatch(
            new DeleteDialog($actor, $id)
        ));
        return $data;
    }
}
