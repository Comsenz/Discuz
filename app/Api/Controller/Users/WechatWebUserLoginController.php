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

use App\Api\Serializer\QrSerializer;
use App\Commands\Users\WebUserQrcode;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatWebUserLoginController extends AbstractResourceController
{
    /**
     * 微信参数
     *
     * @var string
     */
    protected $settings;

    protected $bus;

    public $serializer = QrSerializer::class;

    public function __construct(SettingsRepository $setting, Dispatcher $bus)
    {
        $this->settings = $setting;
        $this->bus = $bus;
    }

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $wx_config = [
            'app_id'=> $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret'=>$this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
        ];

        return $this->bus->dispatch(
            new WebUserQrcode($wx_config)
        );
    }
}
