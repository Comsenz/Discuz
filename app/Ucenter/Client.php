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

use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Client
{
    const UC_CLIENT_RELEASE = '20170101';

    protected $request;

    protected $httpClient;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

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
            'appid' => $this->settings->get('ucenter_appid', 'ucenter') //self::UC_APPID,
        ];
    }

    protected function uc_api_input($data)
    {
        return Authcode::encode($data.'&agent='.md5(Arr::get($this->request->getServerParams(), 'HTTP_USER_AGENT')).'&time='.Carbon::now()->timestamp, $this->settings->get('ucenter_key', 'ucenter'));
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    protected function getHttpClient()
    {
        return $this->httpClient ?? $this->httpClient = new HttpClient([
                'base_uri' => Str::finish($this->settings->get('ucenter_url', 'ucenter'), '/'),
                'timeout'  =>  15
            ]);
    }
}
