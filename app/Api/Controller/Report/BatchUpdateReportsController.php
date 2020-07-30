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

namespace App\Api\Controller\Report;

use App\Api\Serializer\ReportsSerializer;
use App\Commands\Report\BatchEditReport;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdateReportsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ReportsSerializer::class;

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
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $data = $request->getParsedBody()->get('data', []);

        $result = ['data' => [], 'meta' => []];

        collect($data)->each(function ($item) use ($actor) {
            try {
                $result['data'][] = $this->bus->dispatch(
                    new BatchEditReport($actor, $item)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $item, 'message' => $e->getMessage()];
            }
        });

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
