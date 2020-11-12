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

namespace App\Api\Controller\Wallet;

use App\Commands\Wallet\UserWalletCashReview;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserWalletCashReviewController implements RequestHandlerInterface
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
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        // 获取请求的IP
        $ip_address = ip($request->getServerParams());
        $result = $this->bus->dispatch(
            new UserWalletCashReview($actor, $request->getParsedBody(), $ip_address)
        );
        $data = [
            'type' => 'cash-review',
            'data' => [
                'result' => $result
            ],
        ];
        return DiscuzResponseFactory::JsonResponse($data);
    }
}
