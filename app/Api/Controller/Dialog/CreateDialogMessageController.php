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

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogMessageSerializer;
use App\Commands\Dialog\CreateDialogMessage;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateDialogMessageController extends AbstractCreateController
{
    public $serializer = DialogMessageSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var Factory
     */
    protected $validation;

    public $include = ['attachment'];

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
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');
        $this->validation->make($attributes, [
            'dialog_id'     => 'required|int',
            'message_text'  => 'required_without:attachment_id|max:10000',
            'attachment_id' => 'required_without:message_text|int',
        ])->validate();

        return $this->bus->dispatch(
            new CreateDialogMessage($actor, $attributes)
        );
    }
}
