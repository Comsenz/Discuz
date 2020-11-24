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

namespace App\Validators;

use App\Rules\Settings\CashMaxSum;
use App\Rules\Settings\CashMinSum;
use App\Rules\Settings\CashSumLimit;
use App\Rules\Settings\QcloudCaptchaVerify;
use App\Rules\Settings\QcloudClose;
use App\Rules\Settings\QcloudMasterSwitch;
use App\Rules\Settings\QcloudSecretVerify;
use App\Rules\Settings\QcloudSMSVerify;
use App\Rules\Settings\QcloudTaskflowGifVerify;
use App\Rules\Settings\QcloudVodCoverTemplateVerify;
use App\Rules\Settings\QcloudVodTranscodeVerify;
use App\Rules\Settings\QcloudVodVerify;
use App\Rules\Settings\SiteOnlookerPrice;
use App\Rules\Settings\SupportExt;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory;

class SetSettingValidator extends AbstractValidator
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    protected $settings;

    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);

        $this->settings = $settings;
    }

    protected function getRules()
    {
        $rules = [
            'qcloud_secret_id' => ['filled', new QcloudSecretVerify($this->faker('qcloud_secret_key'))],
            'password_length' => ['gte:0'],
            'cash_interval_time' => ['gte:0'],
            'cash_rate' => ['gte:0', 'max:1'],  // 提现手续费率
            'cash_min_sum' => ['gte:0', new CashMinSum($this->faker('cash_max_sum', 0), $this->faker('cash_sum_limit', 0))],
            'cash_max_sum' => ['gte:0', new CashMaxSum($this->faker('cash_sum_limit', 0))],
            'cash_sum_limit' => ['gte:0', new CashSumLimit()],
            'support_img_ext' => [new SupportExt()],
            'support_file_ext' => [new SupportExt()],
            'register_type' => ['in:0,1,2'],
            'qcloud_close' => Arr::has($this->data, 'qcloud_close') ? [new QcloudClose()] : [],
            'qcloud_sms' => Arr::has($this->data, 'qcloud_sms') ? [new QcloudSMSVerify()] : [],
            'qcloud_faceid' => Arr::has($this->data, 'qcloud_faceid') ? [new QcloudMasterSwitch()] : [],
            'qcloud_cms_image' => Arr::has($this->data, 'qcloud_cms_image') ? [new QcloudMasterSwitch()] : [],
            'qcloud_cms_text' => Arr::has($this->data, 'qcloud_cms_text') ? [new QcloudMasterSwitch()] : [],
            'qcloud_cos' => Arr::has($this->data, 'qcloud_cos') ? [new QcloudMasterSwitch()] : [],
            'qcloud_captcha' => Arr::has($this->data, 'qcloud_captcha') ? [new QcloudMasterSwitch()] : [],
            'site_price' => 'required_if:site_mode,pay|nullable|gte:0.1',
        ];

        // 腾讯云验证码特殊处理
        if (Arr::has($this->data, 'qcloud_captcha_app_id') || Arr::has($this->data, 'qcloud_captcha_secret_key')) {
            $rules['qcloud_captcha_app_id'] = [
                'filled',
                new QcloudCaptchaVerify(
                    $this->faker('qcloud_captcha_secret_key'),
                    $this->faker('qcloud_captcha_ticket'),
                    $this->faker('qcloud_captcha_randstr')
                )
            ];
        }

        // 云点播检验
        $this->checkQcloudVod($rules);

        // 开启短信验证
        if (Arr::has($this->data, 'qcloud_sms_app_id')) {
            $rules['qcloud_sms_app_id'] = [
                'filled',
                new QcloudSMSVerify($this->faker('qcloud_sms_app_id'))
            ];
        }

        if (Arr::has($this->data, 'site_onlooker_price')) {
            $rules['site_onlooker_price'] = [new SiteOnlookerPrice()];
        }

        return $rules;
    }

    /**
     * 云点播验证
     *
     * @param $rules
     */
    public function checkQcloudVod(&$rules)
    {
        if (Arr::has($this->data, 'qcloud_vod_sub_app_id')) {
            $rules['qcloud_vod_sub_app_id'] = [new QcloudVodVerify()];
        }

        // 开启视频验证
        if (Arr::has($this->data, 'qcloud_vod') && $this->data['qcloud_vod'] == 1) {
            $rules['qcloud_vod'] = [
                'filled',
                new QcloudVodTranscodeVerify($this->settings->get('qcloud_vod_transcode', 'qcloud'), $this->faker('qcloud_vod_sub_app_id')),
                new QcloudVodVerify($this->settings->get('qcloud_vod_sub_app_id', 'qcloud')),
            ];
        }

        if (Arr::has($this->data, 'qcloud_vod_transcode')) {
            $rules['qcloud_vod_transcode'] = [new QcloudVodTranscodeVerify('', $this->faker('qcloud_vod_sub_app_id'))];
        }
        if (Arr::has($this->data, 'qcloud_vod_cover_template')) {
            $rules['qcloud_vod_cover_template'] = [new QcloudVodCoverTemplateVerify($this->faker('qcloud_vod_sub_app_id'))];
        }
        if (Arr::has($this->data, 'qcloud_vod_taskflow_gif')) {
            $rules['qcloud_vod_taskflow_gif'] = [new QcloudTaskflowGifVerify($this->faker('qcloud_vod_sub_app_id'))];
        }
    }

    /**
     * @return array
     * @property :attribute /resources/lang/zh-CN/validation.php
     */
    protected function getMessages()
    {
        return [
           'site_price.required_if' => trans('setting.site_mode_not_found_price'),
           'site_price.not_in' => trans('setting.site_mode_not_found_price'),
        ];
    }

    /**
     * 伪造未传输的关联字段 - 默认值为空
     *
     * @param $str
     * @param $default
     * @return string|int
     */
    protected function faker($str, $default = '')
    {
        if (!array_key_exists($str, $this->data)) {
            return $default;
        }

        return $this->data[$str];
    }
}
