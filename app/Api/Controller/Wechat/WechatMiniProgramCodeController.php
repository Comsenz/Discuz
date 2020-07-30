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

use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信小程序 - 小程序码
 *
 * @package App\Api\Controller\Wechat
 */
class WechatMiniProgramCodeController implements RequestHandlerInterface
{
    use EasyWechatTrait;

    /**
     * WechatMiniProgramCodeController constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getQueryParams();

        $path = Arr::get($data, 'path', '');
        $width = Arr::get($data, 'width', '');
        $colorR = Arr::get($data, 'r', '');
        $colorG = Arr::get($data, 'g', '');
        $colorB = Arr::get($data, 'b', '');

        $app = $this->miniProgram();

        $response = $app->app_code->get($path, [
            'width' => $width,
            'line_color' => [
                'r' => $colorR,
                'g' => $colorG,
                'b' => $colorB,
            ],
        ]);

        $response = $response->withoutHeader('Content-disposition');

        return $response;
    }
}
