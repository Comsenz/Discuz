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

use App\Common\Utils;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IndexController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $request->getUri()->getPath();

        if (Str::startsWith($route, '/admin')) {
            $file = 'admin.html';
        } else {
            $isMobile = Utils::isMobile($request->getServerParams());
            $file = $isMobile ? 'index.html' : 'pc.html';

            if (Arr::has($request->getQueryParams(), 'from')) {
                $file = 'index.html';
            }

            if (!$isMobile && Str::startsWith($route, '/pages')) {
                $file = Str::replaceFirst("/pages", "/pc-pages", $route) . "/index.html";
            }

            if (!$isMobile && (Str::startsWith($route, '/topic/index') || Str::startsWith($route, '/topic/post'))) {
                $file = Str::replaceFirst("/topic", "/pc-topic", $route) . "/index.html";
            }
        }

        return DiscuzResponseFactory::FileResponse(
            public_path($file)
        );
    }
}
