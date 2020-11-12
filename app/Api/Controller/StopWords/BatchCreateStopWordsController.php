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

namespace App\Api\Controller\StopWords;

use App\Commands\StopWord\BatchCreateStopWord;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BatchCreateStopWordsController implements RequestHandlerInterface
{
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
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $result = $this->bus->dispatch(
            new BatchCreateStopWord($request->getAttribute('actor'), $request->getParsedBody()->get('data', []))
        );

        $data = [
            'data' => [
                'type' => 'stop-words',
                'created' => $result->get('created', 0),    // 新建数量
                'updated' => $result->get('updated', 0),    // 修改数量
                'unique' => $result->get('unique', 0),      // 重复数量
            ],
        ];

        return DiscuzResponseFactory::JsonResponse($data);
    }
}
