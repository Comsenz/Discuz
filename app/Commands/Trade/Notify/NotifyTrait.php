<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade\Notify;

use App\Models\Order;
use App\Models\PayNotify;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;

trait NotifyTrait
{
    /**
     * 支付成功，后续操作
     * @param  string $payment_sn 订单编号
     * @param  string $trade_no 支付平台交易号
     * @param  SettingsRepository ￥setting 配置
     * @return Order
     */
    public function paymentSuccess($payment_sn, $trade_no, SettingsRepository $setting)
    {
        //查询订单
        $order_info = Order::where('status', Order::ORDER_STATUS_PENDING)->where('payment_sn', $payment_sn)->first();
        if (!empty($order_info)) {
            //修改通知数据
            $pay_notify_result = PayNotify::where('payment_sn', $payment_sn)
                ->update(['status' => PayNotify::NOTIFY_STATUS_RECEIVED, 'trade_no' => $trade_no]);
            //修改订单,已支付
            // Order::where('payment_sn', $payment_sn)->update(['status' => Order::ORDER_STATUS_PAID]);
            $order_info->status = Order::ORDER_STATUS_PAID;
            $order_info->save();

            if ($order_info->type == Order::ORDER_TYPE_REGISTER) {
                //注册时，返回支付成功。
                return $order_info;
            } elseif ($order_info->type == Order::ORDER_TYPE_REWARD) {
                //打赏
                $order_amount = $order_info->amount;//订单金额
                //站长作者分成配置
                $site_author_scale = $setting->get('site_author_scale');
                $site_master_scale = $setting->get('site_master_scale');

                $payee_amount = 0.00;//收款人分成金额
                if ($site_author_scale > 0
                    && $site_master_scale > 0
                    && ($site_author_scale + $site_master_scale) == 10) {
                    $author_ratio = $site_author_scale / 10;
                    $payee_amount = sprintf("%.2f", ($order_amount * $author_ratio));
                } else {
                    //未设置或设置错误时站长分成为0,被打赏人分得全部
                    $payee_amount = $order_amount;
                }

                if ($payee_amount > 0) {
                    //收款人钱包可用金额增加
                    $payee_id                      = $order_info->payee_id; //收款人
                    $user_wallet                   = UserWallet::lockForUpdate()->find($payee_id);
                    $user_wallet->available_amount = $user_wallet->available_amount + $payee_amount;
                    $user_wallet->save();
                    //收款人钱包明细记录
                    $payee_change_available_amount = $payee_amount;
                    $change_type                   = '';
                    $change_type_lang              = '';
                    if ($order_info->type = Order::ORDER_TYPE_REWARD) {
                        //打赏收入
                        $change_type      = UserWalletLog::TYPE_INCOME_REWARD;
                        $change_type_lang = 'wallet.reward_income';
                    }
                    //添加钱包明细
                    $user_wallet_log = UserWalletLog::createWalletLog(
                        $payee_id,
                        $payee_change_available_amount,
                        0,
                        $change_type,
                        app('translator')->get($change_type_lang),
                        null,
                        $order_info->id
                    );
                }
                return $order_info;
            }
        }
        return false;
    }
}
