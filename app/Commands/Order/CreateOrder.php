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
        // $this->assertCan($this->actor, 'createCircle');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'amount'   => 'required|numeric|min:0.01',
            'type'     => 'required|int',
            'type_id'  => 'required|int',
            'payee_id' => 'required|int',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        //收款人
        $payee_id = (int)$this->data->get('payee_id');
        $payee_user = User::find($payee_id);
        if (empty($payee_user)) {
            throw new ErrorException(app('translator')->get('order.payee_not_found'), 500);
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
            //订单金额处理
            $amount = sprintf("%.2f", floatval($this->data->get('amount')));

            $order->payment_sn = $payment_sn;
            $order->order_sn   = $this->getOrderSn($notify_id);
            $order->amount     = $amount;
            $order->user_id    = $this->actor->id;
            $order->type       = $this->data->get('type');
            $order->type_id    = $this->data->get('type_id');
            $order->payee_id   = $payee_id;
            $order->remark     = $this->data->get('remark');
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
