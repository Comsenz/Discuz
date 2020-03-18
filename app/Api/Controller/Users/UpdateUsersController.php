<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Users\UpdateUser;
use App\Models\User;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateUsersController extends AbstractListController
{
    public $serializer = InfoSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $multipleData = Arr::get($request->getParsedBody(), 'data', []);

        $list = collect();
        foreach ($multipleData as $data) {
            $id = Arr::get($data, 'attributes.id');

            try {
                $item = $this->bus->dispatch(
                    new UpdateUser($id, ['data' => $data], $actor)
                );

                $item->succeed = true;
            } catch (\Exception $e) {
                $item = new User;

                $item->id = $id;
                $item->succeed = false;
                $item->error = $e->getMessage();
            }

            $list->push($item);
        }

        return $list;
    }
}
