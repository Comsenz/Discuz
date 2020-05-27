<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Setting;

use App\Events\Setting\Saving;
use Illuminate\Validation\Factory as Validator;
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
        $settings = $event->settings->pluck('value', 'key');

        $this->validator->make([
            'watermark' => $settings->get('watermark'),
            'position' => $settings->get('position'),
            'horizontal_spacing' => $settings->get('horizontal_spacing'),
            'vertical_spacing' => $settings->get('vertical_spacing'),
        ], [
            'watermark' => 'nullable|boolean',
            'position' => 'exclude_if:watermark,false|required|integer|between:1,9',
            'horizontal_spacing' => 'exclude_if:watermark,false|required|integer|between:0,9999',
            'vertical_spacing' => 'exclude_if:watermark,false|required|integer|between:0,9999',
        ])->validate();
    }
}
