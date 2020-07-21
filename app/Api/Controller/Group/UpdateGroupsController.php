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

use App\Api\Serializer\GroupSerializer;
use App\Commands\Group\UpdateGroup;
use Discuz\Api\Controller\AbstractListController;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UpdateGroupsController extends AbstractListController
{
    public $serializer = GroupSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $multipleData = Arr::get($request->getParsedBody(), 'data', []);

        $list = collect($multipleData)->reduce(function ($carry, $item) use ($request) {
            $carry = $carry ? $carry : ['data' => [], 'meta' => []];
            try {
                $group = $this->bus->dispatch(
                    new UpdateGroup(
                        Arr::get($item, 'attributes.id'),
                        $request->getAttribute('actor'),
                        $item
                    )
                );
                $carry['data'][] = $group;
                return $carry;
            } catch (Exception $e) {
                $item['attributes']['message'] = $e->getMessage();
                $carry['meta'][] = Arr::get($item, 'attributes');
                return $carry;
            }
        });

        $document->setMeta($list['meta']);

        return $list['data'];
    }
}
