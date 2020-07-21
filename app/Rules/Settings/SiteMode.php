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
 * 站点付费 - 验证
 *
 * Class SiteMode
 * @package App\Rules\Settings
 */
class SiteMode extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $sitePrice;

    public function __construct($sitePrice)
    {
        $this->sitePrice = $sitePrice;
    }

    /**
     * 判断开启站点付费时,价格不能为空
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == 'pay') {
            // 验证 价格不能为空
            if (empty($this->sitePrice)) {
                $this->message = 'site_mode_not_found_price';
                return false;
            }
        }

        return true;
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
