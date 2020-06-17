<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

class BaseParser
{
    private static $data;

    public static function setData($data)
    {
        self::$data = $data;
    }

    public static function getData()
    {
        self::$data;
    }
}
