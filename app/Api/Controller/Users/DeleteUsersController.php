<?php

namespace App\Api\Controller\Users;


use App\Api\Serializer\InfoSerializer;
use App\Commands\Users\DeleteUsers;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;


class DeleteUsersController extends AbstractListController
{

    public $serializer = InfoSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $data = collect();
        foreach($attributes['id'] as $id) {
            $data->push($this->bus->dispatch(
                new DeleteUsers($id, $request->getAttribute('actor'))
            ));
        }

        return $data;
    }
}
