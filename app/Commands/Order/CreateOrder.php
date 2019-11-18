<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateOrder.php XXX 2019-10-16 16:52 zhouzhou $
 */

namespace App\Commands\Order;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\ErrorException;
use Exception;
use App\Models\Order;
use App\Models\PayNotify;
use App\Models\User;
use App\Models\Thread;

class CreateOrder
{

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
     * 初始化命令参数
     * @param User   $actor        执行操作的用户.
     * @param Collection  $data         请求的数据.
     */
    public function __construct($actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @return order
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db)
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createOrder');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'type'     => 'required|int',
            'type_id'  => 'required_if:type,'. Order::ORDER_TYPE_REWARD. '|int',
            'amount'  => 'required_if:type,'. Order::ORDER_TYPE_REWARD. '|numeric|min:0.01'
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        //订单类型
        $order_type = (int)$this->data->get('type');

        //收款款人
        $payee_id = '';
        //收款/付款金额
        $amount = 0;
        switch ($order_type) {
            case Order::ORDER_TYPE_REGISTER:
                //注册订单
                $payee_id = Order::REGISTER_PAYEE_ID;
                //订单金额处理
                $amount = 0.03;//setting获取;
                break;
            case Order::ORDER_TYPE_REWARD:
                //主题打赏订单
                $type_id = (int)$this->data->get('type_id');
                //主题
                $thread = Thread::find($type_id);
                if (empty($thread)) {
                    throw new ErrorException(app('translator')->get('order.order_post_not_found'), 500);
                } else {
                    $payee_id = $thread->user_id;
                    //打赏金额
                    $amount = sprintf("%.2f", (float)$this->data->get('amount'));
                }
                break;
            default:
                throw new ErrorException(app('translator')->get('order.order_type_not_found'), 500);
                break;
        }
        //开始事务
        $db->beginTransaction();
        try {
            $payment_sn             = $this->getPaymentSn();
            $pay_notify             = new PayNotify();
            $pay_notify->payment_sn = $payment_sn;
            $pay_notify->user_id    = $this->actor->id;
            $pay_notify->save();
            $notify_id = $pay_notify->id;

            $order = new Order();
            $order->payment_sn = $payment_sn;
            $order->order_sn   = $this->getOrderSn($notify_id);
            $order->amount     = $amount;
            $order->user_id    = $this->actor->id;
            $order->type       = $this->data->get('type');
            $order->type_id    = $this->data->get('type_id');
            $order->payee_id   = $payee_id;
            $order->remark     = '';
            $order->status     = 0; //待支付
            // 保存订单
            $order->save();
            //提交事务
            $db->commit();
            // 返回数据对象
            return $order;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
            throw new ErrorException(app('translator')->get('order.create_failure'), 500);
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
     * @param int $notify_id 通知自增ID
     * @return string
     */
    public function getOrderSn($notify_id)
    {
        return (date('y', time()) % 9 + 1) . sprintf('%015d', $notify_id);
    }

}
