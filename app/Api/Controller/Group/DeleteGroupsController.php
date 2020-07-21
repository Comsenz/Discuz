<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Group\DeleteGroup;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class DeleteGroupsController extends AbstractListController
{
    public $serializer = InfoSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $multipleData = Arr::get($request->getParsedBody(), 'data.id', []);

        $list = collect();
        foreach ($multipleData as $id) {
            $list->push(
                $this->bus->dispatch(
                    new DeleteGroup(
                        $id,
                        $request->getAttribute('actor')
                    )
                )
            );
        }

        return $list;
    }
}
