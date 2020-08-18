<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class PayParser extends BaseParser
{
    public static function checkPayID(array $data)
    {
        $block_pay_id = Arr::get($data, 'blockPayid');
        if (empty($block_pay_id) || strlen($block_pay_id) != 36) {
           return (string) Str::uuid();
        }
        return $block_pay_id;
    }
}
