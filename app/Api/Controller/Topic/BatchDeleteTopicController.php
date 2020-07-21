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

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Commands\Topic\DeleteTopic;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchDeleteTopicController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = TopicSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @inheritDoc
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertAdmin($actor);

        $ids = explode(',', Arr::get($request->getQueryParams(), 'ids'));
        $idsCollect = collect($ids);

        $result = ['data' => [], 'meta' => []];
        $idsCollect->each(function ($id) use ($actor, &$result) {
            try {
                $result['data'][] = $this->bus->dispatch(
                    new DeleteTopic($id, $actor)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
            }
        });

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
