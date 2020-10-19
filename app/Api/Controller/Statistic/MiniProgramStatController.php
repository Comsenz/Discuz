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

namespace App\Api\Controller\Statistic;

use Discuz\Http\DiscuzResponseFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;

class MiniProgramStatController implements RequestHandlerInterface
{
    private function getHttpClient()
    {
        return new Client();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $client = $this->getHttpClient();

        try {
            $body = $request->getBody();
            $client->post('https://h5.udrig.com/app/wx/v1', [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
        } catch (\Exception $e) {
        }

        try {
            $body = $request->getParsedBody();
            $client->get('https://discuzq-0gxi1bn2969fa48d.service.tcloudbase.com/access?pt=mp-weixin&dn=' . $body[0]['app']['channel']);
        } catch (\Exception $e) {
        }

        return DiscuzResponseFactory::JsonResponse(['status' => "ok"]);
    }
}
