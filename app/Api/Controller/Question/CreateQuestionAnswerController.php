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

namespace App\Api\Controller\Question;

use App\Api\Serializer\QuestionAnswerSerializer;
use App\Commands\Question\CreateQuestionAnswer;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateQuestionAnswerController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = QuestionAnswerSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [];

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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $ip = ip($request->getServerParams());
        $port = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);

        $data = $request->getParsedBody()->get('data', []);
        $data += ['question_id' => Arr::get($request->getQueryParams(), 'question_id')];

        return $this->bus->dispatch(
            new CreateQuestionAnswer($actor, $data, $ip, $port)
        );
    }
}
