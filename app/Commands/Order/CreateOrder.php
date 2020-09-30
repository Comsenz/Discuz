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

namespace App\Commands\Order;

use App\Events\Group\PaidGroup;
use App\Exceptions\OrderException;
use App\Models\Group;
use App\Models\Order;
use App\Models\PayNotify;
use App\Models\Question;
use App\Models\Thread;
use App\Models\User;
use App\Settings\SettingsRepository;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class CreateOrder
{
    use AssertPermissionTrait;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var Collection
     */
    public $data;

    /**
     * @var Dispatcher
     */
    public $events;

    /**
     * @param User $actor
     * @param Collection $data
     */
    public function __construct(User $actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * @param Validator $validator
     * @param ConnectionInterface $db
     * @param SettingsRepository $setting
     * @param Dispatcher $events
     * @return order
     * @throws OrderException
     * @throws ValidationException
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db, SettingsRepository $setting, Dispatcher $events)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'order.create');

        $this->data = collect(Arr::get($this->data, 'data.attributes'));

        $validator_info = $validator->make($this->data->toArray(), [
            'group_id'  => 'filled|int',
            'type'          => 'required|int',
            'thread_id'     => 'required_if:type,' . Order::ORDER_TYPE_REWARD . ',' . Order::ORDER_TYPE_THREAD . '|int',
            'amount'        => 'required_if:type,' . Order::ORDER_TYPE_REWARD . '|numeric|min:0.01',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $orderType = (int) $this->data->get('type');
        $order_zero_amount_allowed = false;//是否允许金额为0
        switch ($orderType) {
            // 注册订单
            case Order::ORDER_TYPE_REGISTER:
                $payeeId = Order::REGISTER_PAYEE_ID;
                $amount = sprintf('%.2f', (float) $setting->get('site_price'));

                // 查询是否有上级邀请 -> 注册分成
                if ($this->actor->isAllowScale(Order::ORDER_TYPE_REGISTER)) {
                    $be_scale = $this->actor->userDistribution->be_scale;
                }
                break;

            // 主题打赏订单
            case Order::ORDER_TYPE_REWARD:
                /** @var Thread $thread */
                $thread = Thread::query()
                    ->where('id', $this->data->get('thread_id'))
                    ->where('price', 0)                             // 免费主题才可以打赏
                    ->where('is_approved', Thread::APPROVED)
                    ->whereNull('deleted_at')
                    ->first();

                if ($thread) {
                    // 主题作者是否允许被打赏
                    $this->assertCan($thread->user, 'canBeReward');

                    $payeeId = $thread->user_id;
                    $amount = sprintf('%.2f', (float) $this->data->get('amount'));

                    // 判断权限是否可以邀请用户分成，查询收款人是否有上级邀请
                    if ($thread->user->can('other.canInviteUserScale') && $thread->user->isAllowScale(Order::ORDER_TYPE_REWARD)) {
                        $be_scale = $thread->user->userDistribution->be_scale;
                    }
                } else {
                    throw new OrderException('order_post_not_found');
                }
                break;

            // 付费主题订单
            case Order::ORDER_TYPE_THREAD:
                // 根据主题 id 查询非自己的付费主题
                /** @var Thread $thread */
                $thread = Thread::query()
                    ->where('id', $this->data->get('thread_id'))
                    ->where('user_id', '<>', $this->actor->id)
                    ->where('price', '>', 0)
                    ->where('is_approved', Thread::APPROVED)
                    ->whereNull('deleted_at')
                    ->first();

                // 根据主题 id 查询是否已付过费
                $order = Order::query()
                    ->where('thread_id', $this->data->get('thread_id'))
                    ->where('user_id', $this->actor->id)
                    ->where('status', Order::ORDER_STATUS_PAID)
                    ->where('type', Order::ORDER_TYPE_THREAD)
                    ->exists();

                // 主题存在且未付过费
                if ($thread && ! $order) {
                    $payeeId = $thread->user_id;
                    $amount = $thread->price;

                    // 查询收款人是否有上级邀请
                    if ($thread->user->can('other.canInviteUserScale') && $thread->user->isAllowScale(Order::ORDER_TYPE_THREAD)) {
                        $be_scale = $thread->user->userDistribution->be_scale;
                    }
                } else {
                    throw new OrderException('order_post_not_found');
                }
                break;
            // 付费用户组
            case Order::ORDER_TYPE_GROUP:
                $order_zero_amount_allowed = true;
                $group_id = $this->data->get('group_id');
                if (in_array($group_id, Group::PRESET_GROUPS)) {
                    throw new OrderException('order_group_forbidden');
                }

                if (!$setting->get('site_pay_group_close')) {
                    //权限购买开关未开启
                    throw new OrderException('order_pay_group_closed');
                }

                /** @var Group $group */
                $group = Group::query()->find($group_id);
                if (
                    isset($group->days)
                    && $group->days > 0
                    && $group->is_paid == Group::IS_PAID
                    && $group->fee > 0
                ) {
                    $payeeId = Order::REGISTER_PAYEE_ID;
                    $amount = $group->fee;
                } else {
                    throw new OrderException('order_group_error');
                }
                break;
            // 问答提问支付
            case Order::ORDER_TYPE_QUESTION:
                // 判断是否允许发布问答帖
                $this->assertCan($this->actor, 'createThreadQuestion');

                // 创建订单
                $amount = sprintf('%.2f', (float) $this->data->get('amount')); // 设置订单问答价格
                $payeeId = $this->data->get('payee_id'); // 设置收款人 (回答人)

                break;
            // 问答围观付费
            case Order::ORDER_TYPE_ONLOOKER:
                /** @var Thread $thread */
                $thread = Thread::query()
                    ->where('id', $this->data->get('thread_id'))
                    ->where('price', 0)  // 问答的帖子价格是0
                    ->where('is_approved', Thread::APPROVED)
                    ->where('type', Thread::TYPE_OF_QUESTION)
                    ->whereNull('deleted_at')
                    ->first();

                if ($thread && $thread->question) {
                    // 查询是否已经围观过，一个用户只允许围观一次
                    if ($thread->onlookerState($this->actor)->exists()) {
                        throw new Exception(trans('order.order_question_onlooker_seen'));
                    }
                    // 判断该问答是否允许围观
                    if (! $thread->question->is_onlooker) {
                        throw new Exception(trans('order.order_question_onlooker_reject'));
                    }
                    // 判断该问题是否已被回答才能围观
                    if ($thread->question->is_answer != Question::TYPE_OF_ANSWERED) {
                        throw new Exception(trans('order.order_question_onlooker_unanswered'));
                    }

                    // 主题的围观单价
                    $amount = $thread->question->onlooker_unit_price; // 主题的围观单价

                    // 设置收款人
                    $payeeId = $thread->user_id; // 提问人
                    $thirdPartyId = $thread->question->be_user_id; // 第三者收益人（回答人）
                } else {
                    throw new OrderException('order_post_not_found');
                }
                break;
            //付费附件
            case Order::ORDER_TYPE_ATTACHMENT:
                /** @var Thread $thread */
                $thread = Thread::query()
                    ->where('id', $this->data->get('thread_id'))
                    ->where('user_id', '<>', $this->actor->id)
                    ->where('attachment_price', '>', 0)
                    ->where('is_approved', Thread::APPROVED)
                    ->whereNull('deleted_at')
                    ->first();

                // 根据主题 id 查询是否已付过费
                $order = Order::query()
                    ->where('thread_id', $this->data->get('thread_id'))
                    ->where('user_id', $this->actor->id)
                    ->where('status', Order::ORDER_STATUS_PAID)
                    ->where('type', Order::ORDER_TYPE_ATTACHMENT)
                    ->exists();

                if ($thread && ! $order && $thread->attachment_price > 0) {
                    $payeeId = $thread->user_id;
                    $amount = $thread->attachment_price;

                    // 付费附件也是用主题的分成权限。查询收款人是否有上级邀请
                    if ($thread->user->can('other.canInviteUserScale') && $thread->user->isAllowScale(Order::ORDER_TYPE_THREAD)) {
                        $be_scale = $thread->user->userDistribution->be_scale;
                    }
                } else {
                    throw new OrderException('order_thread_attachment_error');
                }
                break;
            default:
                throw new OrderException('order_type_error');
        }

        // 订单金额需检查
        if (($amount == 0 && ! $order_zero_amount_allowed) || $amount < 0) {
            throw new OrderException('order_amount_error');
        }

        // 是否匿名
        $is_anonymous = (bool) $this->data->get('is_anonymous');

        // 支付编号
        $payment_sn = $this->getPaymentSn();

        // 支付通知
        $pay_notify             = new PayNotify();
        $pay_notify->payment_sn = $payment_sn;
        $pay_notify->user_id    = $this->actor->id;

        // 订单 amount、payeeId 必须定义值
        $order                  = new Order();
        $order->payment_sn      = $payment_sn;
        $order->order_sn        = $this->getOrderSn();
        $order->amount          = $amount;
        $order->be_scale        = $be_scale ?? 0;
        $order->third_party_id = $thirdPartyId ?? 0; // 第三者收益人
        $order->user_id         = $this->actor->id;
        $order->type            = $orderType;
        $order->thread_id       = isset($thread) ? $thread->id : null;
        $order->group_id        = isset($group_id) ? $group_id : null;
        $order->payee_id        = $payeeId;
        $order->is_anonymous    = $is_anonymous;
        $order->status          = 0; // 待支付

        // 开始事务
        $db->beginTransaction();
        try {
            if ($amount == 0 && $order_zero_amount_allowed) {
                //用户组0付费
                $order->status = 1;
            }
            $pay_notify->save();    // 保存通知数据
            $order->save();         // 保存订单

            if ($orderType == Order::ORDER_TYPE_GROUP && $order->status == 1) {
                $this->events->dispatch(
                    new PaidGroup($order->group_id, $this->actor, $order)
                );
            }
            $db->commit();          // 提交事务
            return $order;
        } catch (Exception $e) {
            $db->rollback();        // 回滚事务
            throw new OrderException('order_create_failure');
        }
    }

    /**
     * 生成支付编号
     * @return string  18位字符串
     */
    public function getPaymentSn()
    {
        return date('Ymd')
        . str_pad(strval(mt_rand(1, 99)), 2, '0', STR_PAD_LEFT)
        . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 生成订单编号
     * @return string 22位字符串
     */
    public function getOrderSn()
    {
        return date('YmdHis', time()) . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
