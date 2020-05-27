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
            'watermark' => $settings->get('watermark', 0),
            'position' => $settings->get('position', 1),
            'horizontal_spacing' => $settings->get('horizontal_spacing', 0),
            'vertical_spacing' => $settings->get('vertical_spacing', 0),
        ], [
            'watermark' => 'required|integer|between:0,1',
            'position' => 'required_if:watermark,1|integer|between:1,9',
            'horizontal_spacing' => 'required_if:watermark,1|integer|between:0,9999',
            'vertical_spacing' => 'required_if:watermark,1|integer|between:0,9999',
        ])->validate();
    }
}
