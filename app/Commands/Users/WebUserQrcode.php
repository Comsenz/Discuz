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

namespace App\Commands\Users;

use App\Exceptions\QrcodeImgException;
use App\Models\SessionToken;
use Discuz\Wechat\EasyWechatTrait;

class WebUserQrcode
{
    use EasyWechatTrait;

    /**
     * 微信参数
     *
     * @var string
     */
    protected $wx_config;

    public function __construct(array $wx_config)
    {
        $this->wx_config = $wx_config;
    }

    /**
     * @return array
     * @throws QrcodeImgException
     */
    public function handle()
    {
        $app = $this->offiaccount($this->wx_config);
        $token = SessionToken::generate('wechat');
        $result = $app->qrcode->temporary($token->token, 60*5);
        $url = $app->qrcode->url($result['ticket']);
        if (!$token->save()) {
            throw new QrcodeImgException(trans('login.WebUser_img_error'));
        }

        return [
            'scene_str' => $token->token,
            'img' => $url,
        ];
    }
}
