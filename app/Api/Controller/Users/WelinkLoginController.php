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

namespace App\Api\Controller\Users;

use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Api\Serializer\SessionSerializer;
use Illuminate\Support\Arr;
use Discuz\Contracts\Socialite\Factory;

class WelinkLoginController extends AbstractResourceController
{
    public $serializer = SessionSerializer::class;

    protected $socialite;

    public function __construct(Factory $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // $sessionId = Arr::get($request->getQueryParams(), 'sessionId', Str::random());
        $code = Arr::get($request->getQueryParams(), 'code');
        $request = $request->withAttribute('code', $code);
        $this->socialite->setRequest($request);
        $driver = $this->socialite->driver('welink');
        $user = $driver->user();
        dd($user);
        return ['sessionId' => '11'];
    }
}
