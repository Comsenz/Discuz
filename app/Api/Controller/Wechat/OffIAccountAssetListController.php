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

use App\Api\Serializer\OffIAccountAssetSerializer;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Support\Collection;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetListController extends AbstractCreateController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var string
     */
    public $serializer = OffIAccountAssetSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param Dispatcher $bus
     * @param UrlGenerator $url
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url)
    {
        $this->bus = $bus;
        $this->url = $url;

        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|Collection|mixed|object|ResponseInterface|string
     * @throws PermissionDeniedException
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        // 素材的类型，图片（image）、视频（video）、语音（voice）、图文（news）
        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);     // 返回素材的数量
        $offset = $this->extractOffset($request);

        $type = Arr::get($filter, 'type');

        return $this->easyWechat->material->list($type, $offset, $limit);
    }
}
