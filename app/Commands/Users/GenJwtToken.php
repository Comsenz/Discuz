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

namespace App\Commands\Users;

use App\Api\Controller\Oauth2\AccessTokenController;
use Discuz\Api\Client;
use Illuminate\Support\Arr;

class GenJwtToken
{
    protected $data;

    protected static $uid;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(Client $apiClient)
    {
        $param = [
            'grant_type' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'scope' => '',
            'username' => Arr::get($this->data, 'username', ''),
            'password' => Arr::get($this->data, 'password', '')
        ];

        return $apiClient->send(AccessTokenController::class, null, [], $param);
    }

    public static function setUid($uid) {
        self::$uid = $uid;
    }

    public static function getUid() {
        return self::$uid;
    }
}
