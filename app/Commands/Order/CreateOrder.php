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
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
     *
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
        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'order.create');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'type'      => 'required|int',
            'thread_id' => 'required_if:type,' . Order::ORDER_TYPE_REWARD . '|int',
            'amount'    => 'required_if:type,' . Order::ORDER_TYPE_REWARD . '|numeric|min:0.01',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        //订单类型
        $order_type = (int) $this->data->get('type');
        $thread_id = '';
        //收款款人
        $payee_id = '';
        //收款/付款金额
        $amount = 0;

        switch ($order_type) {
            // 注册订单
            case Order::ORDER_TYPE_REGISTER:
                $payee_id = Order::REGISTER_PAYEE_ID;
                $amount = sprintf('%.2f', (float) $setting->get('site_price'));
                if ($amount <= 0) {
                    throw new OrderException('order_amount_error');
                }
                break;

            // 主题打赏订单
            case Order::ORDER_TYPE_REWARD:
                $thread_id =  $this->data->get('thread_id');
                $thread = Thread::find($thread_id);

                if (empty($thread)) {
                    throw new OrderException('order_post_not_found');
                } else {
                    $payee_id = $thread->user_id;
                    $amount = sprintf('%.2f', (float) $this->data->get('amount'));

                }
                break;

            // 付费主题订单
            case Order::ORDER_TYPE_THREAD:
                $thread_id =  $this->data->get('thread_id');
                $thread = Thread::find($thread_id);

                if (empty($thread)) {
                    throw new OrderException('order_post_not_found');
                } else {
                    $payee_id = $thread->user_id;
                    $amount = sprintf('%.2f', (float) $thread->price);
                    if ($amount <= 0) {
                        throw new OrderException('order_amount_error');
                    }
                }
                break;

            default:
                throw new OrderException('order_type_error');
                break;
        }

        if ($amount <= 0) {
            throw new OrderException('order_amount_error');
        }

        //支付通知
        $payment_sn             = $this->getPaymentSn();
        $pay_notify             = new PayNotify();
        $pay_notify->payment_sn = $payment_sn;
        $pay_notify->user_id    = $this->actor->id;
        //订单
        $order             = new Order();
        $order->payment_sn = $payment_sn;
        $order->order_sn   = $this->getOrderSn();
        $order->amount     = $amount;
        $order->user_id    = $this->actor->id;
        $order->type       = $order_type;
        $order->thread_id  = $thread_id ? $thread_id : null;
        $order->payee_id   = $payee_id;
        $order->status     = 0; //待支付
        //开始事务
        $db->beginTransaction();
        try {
            //保存通知数据
            $pay_notify->save();
            //保存订单
            $order->save();
            //提交事务
            $db->commit();
            //返回数据对象
            return $order;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
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
