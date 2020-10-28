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

use App\Api\Serializer\WechatPcQrCodeSerializer;
use App\Models\SessionToken;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Wechat\EasyWechatTrait;
use Endroid\QrCode\QrCode;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatPcQrCodeController extends AbstractResourceController
{
    use EasyWechatTrait;
    use AssertPermissionTrait;

    public $serializer = WechatPcQrCodeSerializer::class;

    public $optionalInclude = [];

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $type = Arr::get($request->getQueryParams(), 'type');

        // Generate parameters
        $params = [];

        switch ($type) {
            default:
            case 'pc_login':
                $identifier = SessionToken::WECHAT_PC_LOGIN;
                $route = '/pages/user/pc-login';
                $token = SessionToken::generate($identifier);
                break;
            case 'pc_relation':
                $actor = $request->getAttribute('actor');
                $this->assertRegistered($actor);
                $identifier = SessionToken::WECHAT_PC_BIND;
                $route = '/pages/user/pc-relation';
                $token = SessionToken::generate($identifier, null, $actor->id);
                break;
        }

        // create token
        $token->save();

        $locationUrl = $this->url->action($route, array_merge($params, ['session_token' => $token->token]));

        $qrCode = new QrCode($locationUrl);

        $binary = $qrCode->writeString();

        $baseImg = 'data:image/png;base64,' . base64_encode($binary);

        return [
            'session_token' => $token->token,
            'base64_img' => $baseImg,
        ];
    }
}
