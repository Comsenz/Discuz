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

use App\Models\SessionToken;
use Discuz\Http\DiscuzResponseFactory;
use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WechatPcBindPollController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sessionToken = Arr::get($request->getQueryParams(), 'session_token');

        $token = SessionToken::get($sessionToken);
        if (empty($token)) {
            // 二维码已失效，扫码超时
            throw new Exception('pc_qrcode_time_out');
        }

        if (is_null($token->payload)) {
            // 扫码中
            throw new Exception('pc_qrcode_scanning_code');
        }

        if (isset($token->payload['bind']) && $token->payload['bind']) {
            // 绑定成功
            return DiscuzResponseFactory::JsonResponse($token->payload);
        }

        throw new Exception($token->payload['code'] ?: 'error');
    }
}
