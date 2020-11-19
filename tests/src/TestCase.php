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

namespace Discuz\Tests;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class TestCase extends \PHPUnit\Framework\TestCase
{
    const API_HOST = 'http://dev.discuss.com/api/';

    protected $http;

    protected $token;


    protected function setUp(): void
    {
        if ($this->token) {
            return;
        }

        $response = $this->http()->post('login', [
            'json' => [
                'data' => [
                    'attributes' => [
                        'username' => 'admin',
                        'password' => 'admin123'
                    ]
                ]
            ]
        ]);

        $auth = json_decode($response->getBody()->getContents(), true);

        $this->token = Arr::get($auth, 'data.attributes.access_token');
    }

    protected function http(): Client
    {

        $headers = [];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer '.$this->token;
        }

        return $this->http ? $this->http : new Client([
            'base_uri' => self::API_HOST,
            'timeout'  =>  10,
            'headers' => $headers
        ]);
    }
}
