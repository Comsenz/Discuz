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

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountMenuSerializer;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountMenuListController extends AbstractCreateController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var string
     */
    public $serializer = OffIAccountMenuSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param Dispatcher $bus
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     */
    public function __construct(Dispatcher $bus, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->bus = $bus;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        return $this->offiaccount()->menu->list();
    }
}
