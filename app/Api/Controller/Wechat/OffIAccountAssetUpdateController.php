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
use App\Validators\OffIAccountAssetUpdateValidator;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Support\Collection;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetUpdateController extends AbstractResourceController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var string
     */
    public $serializer = OffIAccountAssetSerializer::class;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var OffIAccountAssetUpdateValidator
     */
    protected $validator;

    /**
     * @param OffIAccountAssetUpdateValidator $validator
     */
    public function __construct(OffIAccountAssetUpdateValidator $validator)
    {
        $this->validator = $validator;
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|Collection|mixed|object|ResponseInterface|string
     * @throws PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getParsedBody(), 'data.media_id', '');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', '');

        $this->validator->valid($attributes);

        // TODO 指定更新多图文中的第 2 篇
        // $result = $this->easyWechat->material->updateArticle($mediaId, new Article(...), 1); // 第 2 篇

        return $this->easyWechat->material->updateArticle($mediaId, $attributes);
    }
}
