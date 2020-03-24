<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use App\Rules\Settings\CashMaxSum;
use App\Rules\Settings\CashMinSum;
use App\Rules\Settings\CashSumLimit;
use App\Rules\Settings\QcloudCaptchaVerify;
use App\Rules\Settings\QcloudSecretVerify;
use App\Rules\Settings\QcloudVodVerify;
use App\Rules\Settings\SupportExt;
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

    public function __construct(Factory $validator)
    {
        parent::__construct($validator);
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
            'site_mode' => ['in:pay,public'],
            'support_img_ext' => [new SupportExt()],
            'support_file_ext' => [new SupportExt()],
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

        if (Arr::has($this->data, 'qcloud_vod')) {
            $rules['qcloud_vod'] =  ['filled', new QcloudVodVerify()];
        }

        return $rules;
    }

    /**
     * @return array
     * @property :attribute /resources/lang/zh-CN/validation.php
     */
    protected function getMessages()
    {
        return [
//            'password_length.gte' => '密码长度必须大于或等于:value',
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
