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

namespace App\Http\Controller;

use Discuz\Api\Client;
use Illuminate\View\Factory;
use Psr\Http\Message\ServerRequestInterface;
use Discuz\Web\Controller\AbstractWebController;
use App\Api\Controller\Threads\ResourceThreadController;

class DiscussionController extends AbstractWebController
{
    public function render(ServerRequestInterface $request, Factory $view) {
        $response = $this->app->make(Client::class)->send(ResourceThreadController::class, $request->getAttribute('actor'), $request->getQueryParams());

        $this->apiDocument = json_decode($response->getBody(), true);
        return $view->make('app');
    }
}
