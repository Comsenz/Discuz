<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: NotifyTrait.php XXX 2019-11-11 15:10 zhouzhou $
 */

namespace App\Commands\Trade\Notify;

use App\Models\Order;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Models\PayNotify;

trait NotifyTrait
{
	/**
	 * 支付成功，后续操作
	 * @param  string $payment_sn 订单编号
	 * @param  string $trade_no 支付平台交易号
     * @return Order
	 */
	public function paymentSuccess($payment_sn, $trade_no)
	{
        //查询订单
        $order_info = Order::where('status', Order::ORDER_STATUS_PENDING)->where('payment_sn', $payment_sn)->first();
        if (!empty($order_info)) {
	        //修改通知数据
	        $pay_notify_result = PayNotify::where('payment_sn', $payment_sn)
	            ->update(['status' => PayNotify::NOTIFY_STATUS_RECEIVED, 'trade_no' => $trade_no]);
        	//修改订单,已支付
        	Order::where('payment_sn', $payment_sn)->update(['status' => Order::ORDER_STATUS_PAID]);

            if ($order_info->type == Order::ORDER_TYPE_REGISTER) {
                //注册时，返回支付成功。
                return $order_info;
            }
            //订单金额
            $order_amount = $order_info->amount;
            //收款人分成
            $payee_id = $order_info->payee_id;//收款人
        	$user_wallet = UserWallet::lockForUpdate()->find($payee_id);
        	$user_wallet->available_amount = $user_wallet->available_amount + $order_amount;
        	$user_wallet->save();

            //可用金额增加
            $payee_change_available_amount = $order_amount;
            $change_type = '';
            $change_type_lang = '';
            if ($order_info->type = Order::ORDER_TYPE_REWARD) {
            	//打赏收入
            	$change_type = UserWalletLog::TYPE_INCOME_REWARD;
            	$change_type_lang = 'wallet.reward_income';
            }
            //添加钱包明细
            $user_wallet_log = UserWalletLog::createWalletLog($payee_id, $payee_change_available_amount, 0, $change_type, app('translator')->get($change_type_lang));

        	//圈主分成
            //
        	return $order_info;
        }
        return false;
	}
}
