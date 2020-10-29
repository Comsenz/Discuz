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

namespace App\User;

use App\Models\SessionToken;

/**
 * 用户绑定后
 *
 * Class Bound
 * @package App\User
 */
class Bound
{
    public function __construct()
    {
        //
    }

    /**
     * @param $sessionToken
     * @param $accessToken
     * @param $data
     * @return mixed
     */
    public function pcLogin($sessionToken, $accessToken, $data)
    {
        $token = SessionToken::query()->where('token', $sessionToken)->first();

        if (!empty($token)) {
            /** @var SessionToken $token */
            $token->payload = $accessToken;
            $token->user_id = $data['user_id'];
            $token->save();
        }

        return $accessToken;
    }
}
