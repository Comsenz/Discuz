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

namespace App\Hashids;

use Hashids\Hashids as HashidsClass;

/**
 * Class Hashids
 * @package App\Hashids
 */
class Hashids extends HashidsClass
{
    private static $instance = null;

    public static function getInstance($length = 0)
    {
        if (is_null(self::$instance)) {
            // 用 app.key 作为加密盐
            self::$instance = new self(config('app.key'), $length);
        }

        // 验证长度是否改变，重新实例
        if (!$length && self::$instance->minHashLength != $length) {
            self::$instance = new self(config('app.key'), $length);
        }

        return static::$instance;
    }

    /**
     * 加密单个
     *
     * @param $string
     * @param int $length
     * @return mixed
     */
    public function encrypt($string, $length = 0)
    {
        return self::getInstance($length)->encode($string);
    }

    /**
     * 解密单个
     *
     * @param $string
     * @param int $length
     * @return mixed
     */
    public function decrypt($string, $length = 0)
    {
        $codeArr = self::getInstance($length)->decode($string);

        return array_shift($codeArr);
    }
}
