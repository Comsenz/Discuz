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

namespace App\Rules\Settings;

use Discuz\Validation\AbstractRule;

/**
 * 附件公共 - 验证
 *
 * Class SupportExt
 * @package App\Rules\Settings
 */
class SupportExt extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    /**
     * 判断不允许存在的扩展名
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ext = explode(',', $value);

        $suffix = 'php';
        $bool = !in_array($suffix, $ext);

        if (!$bool) {
            $this->message = $attribute . '_' . $suffix . '_format_error';
        }

        return $bool;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('setting.' . $this->message);
    }
}
