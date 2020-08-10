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

namespace App\Listeners\Setting;

use App\Events\Setting\Saving;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class ChangeSiteMode
{
    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @var Validator
     */
    public $validator;

    /**
     * @param SettingsRepository $settings
     * @param Validator $validator
     */
    public function __construct(SettingsRepository $settings, Validator $validator)
    {
        $this->settings = $settings;
        $this->validator = $validator;
    }

    /**
     * @param Saving $event
     * @throws ValidationException
     */
    public function handle(Saving $event)
    {
        $siteMode = Arr::get($event->settings->get('default_site_mode'), 'value');

        if ($siteMode !== $this->settings->get('site_mode')) {
            $this->validator->validate(
                $event->settings->where('tag', 'default')->pluck('value', 'key')->all(),
                [
                    'site_mode' => [
                        'in:pay,public',
                        function ($attribute, $value, $fail) {
                            // 关闭微信支付时，站点模式不能设为付费模式
                            if ($value === 'pay' && ! $this->settings->get('wxpay_close', 'wxpay')) {
                                $fail(trans('setting.pay_site_mode_need_open_payment'));
                            }
                        },
                    ]
                ],
                [
                    'site_mode.in' => trans('setting.invalid_site_mode'),
                ]
            );

            if ($siteMode === 'pay') {
                $this->setChangeTime($event->settings, Carbon::now());
            } elseif ($siteMode === 'public') {
                $this->setChangeTime($event->settings);
            }
        }
    }

    /**
     * @param Collection $settings
     * @param Carbon|null $time
     */
    private function setChangeTime(Collection $settings, Carbon $time = null)
    {
        $settings->put('default_site_pay_time', [
            'key' => 'site_pay_time',
            'value' => $time,
            'tag' => 'default'
        ]);
    }
}
