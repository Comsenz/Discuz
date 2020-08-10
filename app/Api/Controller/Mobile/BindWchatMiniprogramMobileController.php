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

namespace App\Api\Controller\Mobile;

use App\Api\Serializer\UserSerializer;
use App\Commands\Users\BindWchatMiniprogramMobile;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BindWchatMiniprogramMobileController extends AbstractCreateController
{
    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    protected $validation;

    protected $bus;

    /**
     * WechatMiniprogramBindController constructor.
     * @param Dispatcher $bus
     * @param ValidationFactory $validation
     */
    public function __construct(Dispatcher $bus, ValidationFactory $validation)
    {
        $this->validation = $validation;
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws \Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');

        $this->validation->make(
            $attributes,
            ['js_code' => 'required','iv' => 'required','encryptedData' => 'required']
        )->validate();


        return $this->bus->dispatch(new BindWchatMiniprogramMobile($attributes, $actor));
    }
}
