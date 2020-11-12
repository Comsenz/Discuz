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

use App\Models\Order;
use Discuz\Validation\AbstractRule;

/**
 * Class SiteOnlookerPrice
 *
 * @package App\Rules\Settings
 */
class SiteOnlookerPrice extends AbstractRule
{
    public function passes($attribute, $value)
    {
        // 验证
        if ($value == 0) {
            return true;
        }

        $order = new Order();
        $order->amount = $value;
        $onlookerActualPrice = $order->calculateOnlookersAmount(true);
        $onlookerPrice = $onlookerActualPrice / 2;

        // 判断是否是整数
        if (ceil($onlookerPrice) == $onlookerPrice) {
            return true;
        }

        // 如果是小数判断相加合 < 总围观金额
        $arr = explode('.', $onlookerPrice);
        $arrEnd = end($arr);
        // 如果小数点后有大于3位数，说明不能够整除平分
        if (strlen($arrEnd) >= 3) {
            return false;
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
        return trans('setting.onlooker_price_not_divisible');
    }
}
