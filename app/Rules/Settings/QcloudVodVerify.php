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

namespace App\Rules\Settings;

use App\Exceptions\TranslatorException;
use Discuz\Qcloud\QcloudTrait;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云云点播 - 验证
 *
 * Class QcloudVodVerify
 * @package App\Rules\Settings
 */
class QcloudVodVerify extends BaseQcloud
{
    use QcloudTrait;

    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $key;

    private $ticket;

    private $randStr;

    protected $subAppId;

    public function __construct($subAppId = '')
    {
        parent::__construct();

        $this->subAppId = $subAppId;
    }

    /**
     * @param string $attribute
     * @param $value
     * @return bool
     * @throws TencentCloudSDKException|TranslatorException
     */
    public function passes($attribute, $value)
    {
        try {
            //开启视频开关时通过setting的值进行验证
            if ($attribute == 'qcloud_vod') {
                $value = $this->subAppId;
            }

            $this->describeStorageData($value);
        } catch (TencentCloudSDKException $e) {
            if ($e->getCode() == 'FailedOperation.InvalidVodUser') {
                throw new TranslatorException('tencent_vod_subappid_error', [$e->getErrorCode()]);
            } else {
                throw new TranslatorException('tencent_vod_error', [$e->getCode()]);
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
    }

    /**
     * @param $array
     */
    public function errorMessage($array)
    {
    }
}
