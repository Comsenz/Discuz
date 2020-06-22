<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

use Illuminate\Support\Str;

class PayParser extends BaseParser
{
    public static function checkPayID(array $data)
    {
        if (empty($data['blockPayid']) || strlen($data['blockPayid']) != 36) {
           return (string) Str::uuid();
        }
        return $data['blockPayid'];
    }
}
