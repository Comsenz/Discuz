<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Order;

use App\Exceptions\OrderException;
use App\Models\Order;
use App\Models\PayNotify;
use App\Models\Thread;
use App\Models\User;
use App\Settings\SettingsRepository;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use Illuminate\Database\ConnectionInterface;
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
     * @param User $actor 执行操作的用户.
     * @param Collection $data 请求的数据.
     */
    public function __construct($actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @param Validator $validator
     * @param ConnectionInterface $db
     * @param SettingsRepository $setting
     * @return order
     * @throws OrderException
     * @throws ValidationException
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(Validator $validator, ConnectionInterface $db, SettingsRepository $setting)
    {
        $this->assertCan($this->actor, 'order.create');

        $validator_info = $validator->make($this->data->toArray(), [
            'is_anonymous'      => 'filled|int',
            'type'      => 'required|int',
            'thread_id' => 'required_if:type,' . Order::ORDER_TYPE_REWARD . ',' . Order::ORDER_TYPE_THREAD . '|int',
            'amount'    => 'required_if:type,' . Order::ORDER_TYPE_REWARD . '|numeric|min:0.01',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $orderType = (int) $this->data->get('type');

        switch ($orderType) {
            // 注册订单
            case Order::ORDER_TYPE_REGISTER:
                $payeeId = Order::REGISTER_PAYEE_ID;
                $amount = sprintf('%.2f', (float) $setting->get('site_price'));
                break;

            // 主题打赏订单
            case Order::ORDER_TYPE_REWARD:
                $thread = Thread::where('id', $this->data->get('thread_id'))
                    ->where('is_approved', Thread::APPROVED)
                    ->whereNull('deleted_at')
                    ->first();

                if ($thread) {
                    $payeeId = $thread->user_id;
                    $amount = sprintf('%.2f', (float) $this->data->get('amount'));
                } else {
                    throw new OrderException('order_post_not_found');
                }
                break;

            // 付费主题订单
            case Order::ORDER_TYPE_THREAD:
                // 根据主题 id 查询非自己的付费主题
                $thread = Thread::where('id', $this->data->get('thread_id'))
                    ->where('user_id', '<>', $this->actor->id)
                    ->where('price', '>', 0)
                    ->where('is_approved', Thread::APPROVED)
                    ->whereNull('deleted_at')
                    ->first();

                // 根据主题 id 查询是否已付过费
                $order = Order::where('thread_id', $this->data->get('thread_id'))
                    ->where('user_id', $this->actor->id)
                    ->where('status', Order::ORDER_STATUS_PAID)
                    ->where('type', Order::ORDER_TYPE_THREAD)
                    ->exists();

                // 主题存在且未付过费
                if ($thread && ! $order) {
                    $payeeId = $thread->user_id;
                    $amount = $thread->price;
                } else {
                    throw new OrderException('order_post_not_found');
                }
                break;

            default:
                throw new OrderException('order_type_error');
                break;
        }

        // 订单金额需大于 0
        if ($amount <= 0) {
            throw new OrderException('order_amount_error');
        }

        //是否匿名
        $is_anonymous = (int) $this->data->get('is_anonymous');

        // 支付编号
        $payment_sn = $this->getPaymentSn();

        //支付通知
        $pay_notify             = new PayNotify();
        $pay_notify->payment_sn = $payment_sn;
        $pay_notify->user_id    = $this->actor->id;

        //订单
        $order             = new Order();
        $order->payment_sn = $payment_sn;
        $order->order_sn   = $this->getOrderSn();
        $order->amount     = $amount;
        $order->user_id    = $this->actor->id;
        $order->type       = $orderType;
        $order->thread_id  = isset($thread) ? $thread->id : null;
        $order->payee_id   = $payeeId;
        $order->is_anonymous   = $is_anonymous > 0 ? 1 : 0;
        $order->status     = 0; //待支付

        //开始事务
        $db->beginTransaction();
        try {
            $pay_notify->save();    // 保存通知数据
            $order->save();         // 保存订单

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
