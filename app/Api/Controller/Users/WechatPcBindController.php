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
use App\Models\User;
use App\Models\UserWechat;
use Discuz\Http\DiscuzResponseFactory;
use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WechatPcBindController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sessionToken = Arr::get($request->getQueryParams(), 'session_token'); // 轮询 token
        $token = Arr::get($request->getQueryParams(), 'wechat_token'); // 微信信息

        /**
         * @var SessionToken $sessionTokenData
         * @var SessionToken $tokenData
         */
        $sessionTokenData = SessionToken::query()->where('token', $sessionToken)->first();
        $tokenData = SessionToken::query()->where('token', $token)->first();
        if (empty($sessionTokenData) || empty($tokenData)) {
            throw new Exception('session_token_expired'); // session token expired 已过期
        }

        /**
         * 查询 原账户的用户信息
         *
         * @var User $originUser
         */
        $originUser = User::query()->where('id', $sessionTokenData->user_id)->first();
        if (empty($originUser)) {
            throw new Exception('not_found_user'); // 未查询到用户信息
        }
        // change bind 解除绑定原号关系
        if (! empty($originUser->wechat)) {
            $originUser->wechat->user_id = null;
            $originUser->wechat->save();
        }

        /**
         * 查询 要绑定的微信信息
         *
         * @var UserWechat $userWechat
         */
        $userWechat = UserWechat::query()->where('mp_openid', $tokenData->payload['openid'])->first();
        if (empty($userWechat)) {
            throw new Exception('not_found_user_wechat'); // 未查询到微信信息
        }

        // bind
        $userWechat->user_id = $sessionTokenData->user_id;
        $userWechat->save();

        $build = ['bind' => true, 'code' => 'success_bind'];
        $sessionTokenData->payload = $build;
        $sessionTokenData->save();

        // return $accessToken;
        return DiscuzResponseFactory::JsonResponse($build);
    }
}
