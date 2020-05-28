<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Setting;

use App\Events\Setting\Saving;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckWatermarkSetting
{
    /**
     * @var Validator
     */
    public $validator;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Saving $event
     * @throws ValidationException
     */
    public function handle(Saving $event)
    {
        $settings = $event->settings->where('tag', 'watermark')->pluck('value', 'key')->all();

        $watermark = (bool) Arr::get($settings, 'watermark');

        $this->validator->make($settings, [
            'watermark' => 'nullable|boolean',
            'position' => [Rule::requiredIf($watermark), 'integer', 'between:1,9'],
            'horizontal_spacing' => [Rule::requiredIf($watermark), 'integer', 'between:0,9999'],
            'vertical_spacing' => [Rule::requiredIf($watermark), 'integer', 'between:0,9999'],
        ])->validate();
    }
}
