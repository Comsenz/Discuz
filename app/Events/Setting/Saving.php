<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Setting;

use Illuminate\Support\Collection;

class Saving
{
    /**
     * @var Collection
     */
    public $settings;

    /**
     * @param Collection $settings
     */
    public function __construct(Collection &$settings)
    {
        $this->settings = &$settings;
    }
}
