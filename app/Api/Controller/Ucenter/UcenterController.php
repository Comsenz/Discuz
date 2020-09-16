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

namespace App\Api\Controller\Ucenter;

use App\Ucenter\Authcode;
use App\Ucenter\Client;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UcenterController implements RequestHandlerInterface
{
    const API_RETURN_SUCCEED = 1;

    const API_RETURN_FAILED = -1;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $content = '';
        $code = Arr::get($request->getQueryParams(), 'code');

        $get = $post = [];
        parse_str(Authcode::decode($code, $this->settings->get('ucenter_key', 'ucenter')), $get);

        if (Carbon::now()->timestamp - Arr::get($get, 'time') > 3600) {
            $content = 'Authracation has expiried';
        }
        if (empty($get)) {
            $content = 'Invalid Request';
        }

        if (in_array(Arr::get($get, 'action'), ['test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'])) {
            $content = call_user_func([$this, Arr::get($get, 'action')], $get, $post);
        } else {
            $content = self::API_RETURN_FAILED;
        }
        return DiscuzResponseFactory::HtmlResponse((string)$content);
    }

    protected function test($get, $post)
    {
        return self::API_RETURN_SUCCEED;
    }
}
