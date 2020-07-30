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

namespace App\Commands\Qcloud;

use App\Models\User;
use App\Settings\SettingsRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use TencentCloud\Common\Exception\TencentCloudSDKException;

class CreateVodUploadSignature
{
    use AssertPermissionTrait;

    /**
     * 签名过期时间
     */
    const EXPIRETIME = 3600;

    protected $actor;

    protected $data;

    protected $settings;

    public function __construct(User $actor, $data)
    {
        $this->actor            = $actor;
        $this->data             = $data;
    }

    public function handle(SettingsRepository $settings)
    {
        $this->settings = $settings;

        return call_user_func([$this, '__invoke']);
    }

    public function __invoke()
    {
        $this->assertRegistered($this->actor);

        $secretId = $this->settings->get('qcloud_secret_id', 'qcloud');
        $secretKey = $this->settings->get('qcloud_secret_key', 'qcloud');
        $subAppId = $this->settings->get('qcloud_vod_sub_app_id', 'qcloud') ?: 0;

        if (!$this->settings->get('qcloud_close', 'qcloud')) {
            throw new TencentCloudSDKException('tencent_qcloud_close_current');
        }
        if (!$secretId || !$secretKey) {
            throw new PermissionDeniedException;
        }

        $currentTime = Carbon::now()->timestamp;

        $original = [
            'secretId'         => $secretId,
            'currentTimeStamp' => $currentTime,
            'expireTime'       => $currentTime + self::EXPIRETIME,
            'vodSubAppId'       => $subAppId,
            'random'           => rand(),
        ];

        $original = http_build_query($original);
        return [base64_encode(hash_hmac('SHA1', $original, $secretKey, true).$original)];
    }
}
