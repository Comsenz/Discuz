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

namespace App\Ucenter;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client as HttpClient;

class Client
{
    const UC_KEY = '123';

    const UC_CLIENT_RELEASE = '20170101';

    const UC_APPID = '3';

    const UC_IP = '127.0.0.1';

    protected $request;

    protected $httpClient;

    public function uc_user_login($username, $password, $isuid = 0, $checkques = 0, $questionid = '', $answer = '', $ip = '')
    {
        $isuid = intval($isuid);
        $return = call_user_func([$this, 'uc_api_post'], 'user', 'login', ['username'=>$username, 'password'=>$password, 'isuid'=>$isuid, 'checkques'=>$checkques, 'questionid'=>$questionid, 'answer'=>$answer, 'ip' => $ip]);
        return Xml::uc_unserialize($return);
    }

    protected function uc_api_post($module, $action, $arg = [])
    {
        $postdata = $this->uc_api_requestdata($module, $action, http_build_query($arg));
        $response = $this->getHttpClient()->post('index.php', [
            'headers' => [
                'User-Agent' => Arr::get($this->request->getServerParams(), 'HTTP_USER_AGENT')
            ],
            'form_params' => $postdata
        ]);
        return $response->getBody()->getContents();
    }

    protected function uc_api_requestdata($module, $action, $arg = '')
    {
        return [
            'm' => $module,
            'a' => $action,
            'inajax' => 2,
            'release' => self::UC_CLIENT_RELEASE,
            'input' => $this->uc_api_input($arg),
            'appid' => self::UC_APPID,
        ];
    }

    protected function uc_api_input($data)
    {
        return Authcode::encode($data.'&agent='.md5(Arr::get($this->request->getServerParams(), 'HTTP_USER_AGENT')).'&time='.Carbon::now()->timestamp, self::UC_KEY);
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    protected function getHttpClient()
    {
        return $this->httpClient ?? $this->httpClient = new HttpClient([
                'base_uri' => 'http://dev.discuz.com/uc_server/',
                'timeout'  =>  15
            ]);
    }
}
