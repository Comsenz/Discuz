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

use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信公众号 - 获取单条永久素材
 *
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetResourceController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * WechatMiniProgramCodeController constructor.
     */
    public function __construct()
    {
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PermissionDeniedException
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getQueryParams(), 'media_id');
        $type = Arr::get($request->getQueryParams(), 'filter.type');

        // 获取永久素材
        $response = $this->easyWechat->material->get($mediaId);

        /**
         * 根据类型数据不同 返回数据形式&格式不同
         */
        switch ($type) {
            case 'image': // 图片（image）
                header('Content-type: image/jpeg');
                return $response;
            case 'video': // 视频（video）
                if (is_array($response)) {
                    return DiscuzResponseFactory::JsonResponse($response);
                }
                break;
            case 'voice': // 语音（voice）
                header('Content-type: audio/mpeg');
                return $response;
            case 'news':  // 图文（news）
                if (is_array($response)) {
                    return DiscuzResponseFactory::JsonResponse($response);
                }
                break;
        }

        throw new \Exception('Unexpected value');
    }
}
