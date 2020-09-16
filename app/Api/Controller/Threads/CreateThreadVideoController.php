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

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadVideoSerializer;
use App\Commands\Thread\CreateThreadVideo;
use App\Models\Thread;
use App\Models\ThreadVideo;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
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
     *
     * @throws ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $type = (int) Arr::get($attributes, 'type', ThreadVideo::TYPE_OF_VIDEO);

        $this->validation->make($attributes, [
            'file_id' => 'required',
        ])->validate();

        return $this->bus->dispatch(
            new CreateThreadVideo(
                $actor,
                new Thread,
                $type,
                $request->getParsedBody()->get('data', [])
            )
        );
    }
}
