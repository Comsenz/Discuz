<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Validation\Factory;

class ThreadValidator extends AbstractValidator
{
    use QcloudTrait;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * 腾讯云验证码开关。
     *
     * @var bool
     */
    protected $qCloudCaptchaSwitch = false;

    /**
     * @var bool
     */
    protected $qCloudVodSwitch = false;

    /**
     * 获取相关设置
     *
     * @param Factory $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);

        $this->settings = $settings;

        // 获取后台设置的腾讯云验证码开关
        $this->qCloudCaptchaSwitch = (bool)$settings->get('qcloud_captcha', 'qcloud');

        // 获取后台设置的腾讯云验证码开关
        $this->qCloudVodSwitch = (bool)$settings->get('qcloud_vod', 'qcloud');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        $rules = [
            'title' => 'required|min:3|max:80',
            'price' => [
                'sometimes',
                'regex:/^(0|[1-9]\d{0,7})(\.\d{1,2})?$/',   // decimal 10,2
            ],
        ];

        // 当腾讯云验证码开启时，且数据中有 captcha 时，检查验证码
        if ($this->qCloudCaptchaSwitch) {
            $rules['captcha'] = [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value)) == 3) {
                        $result = $this->describeCaptchaResult(...$value);

                        if ($result['CaptchaCode'] != 1) {
                            $fail(trans('validation.wrong') . "({$result['CaptchaCode']})");
                        }
                    } else {
                        $fail(trans('validation.wrong'));
                    }
                },
            ];
        }

        // 发布、更新视频主题时验证
        if ($this->data['type'] == 2) {
            $rules['file_id'] = [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$this->qCloudVodSwitch) {
                        $fail(trans('validation.qcloud_vod'));
                    }
                }
            ];
            $rules['file_name'] = 'required';
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        return [];
    }
}
