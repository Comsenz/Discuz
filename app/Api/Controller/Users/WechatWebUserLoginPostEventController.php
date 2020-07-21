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

use App\Commands\Users\WebUserEvent;
use App\Settings\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WechatWebUserLoginPostEventController implements RequestHandlerInterface
{
    use EasyWechatTrait;

    /**
     * 微信参数
     *
     * @var string
     */
    protected $settings;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(SettingsRepository $setting, Dispatcher $bus)
    {
        $this->settings = $setting;
        $this->bus = $bus;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $wx_config = [
            'token' => $this->settings->get('oplatform_app_token', 'wx_oplatform'),
            'aes_key' => $this->settings->get('oplatform_app_aes_key', 'wx_oplatform')
        ];
        $app = $this->offiaccount($wx_config);
        $this->bus->dispatch(
            new WebUserEvent($app)
        );
        $response  = $app->server->serve();

        return  DiscuzResponseFactory::XmlResponse($response->getContent());
    }
}
