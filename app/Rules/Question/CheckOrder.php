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

namespace App\Rules\Question;

use App\Models\Order;
use Discuz\Validation\AbstractRule;
use Exception;

/**
 * Class CheckOrder
 * @package App\Rules\Question
 */
class CheckOrder extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $actor;

    private $price;

    public function __construct($actor, $price)
    {
        $this->actor = $actor;
        $this->price = $price;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool|mixed
     * @throws Exception
     */
    public function passes($attribute, $value)
    {
        try {
            /** @var Order $order */
            $order = Order::query()->where('order_sn', $value)->firstOrFail();
        } catch (Exception $e) {
            throw new Exception(trans('order.order_not_found'));
        }

        // 订单是否未支付，并且是该主题人支付的
        if ($order->status != Order::ORDER_STATUS_PAID || $this->actor->id != $order->user_id) {
            throw new Exception(trans('order.order_status_fail'));
        }

        // 判断支付的金额是否和帖子设置一致
        if ($this->price != $order->amount) {
            throw new Exception(trans('post.post_question_payment_amount_fail'));
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
