<?php
declare(strict_types = 1);

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Wallet;

use App\Events\Wallet\Cash;
use App\Trade\Config\GatewayConfig;
use App\Trade\TransferTrade;
use App\Settings\SettingsRepository;
use Illuminate\Database\ConnectionInterface;
use App\Models\UserWalletCash;
use App\Models\UserWechat;
use App\Models\UserWalletLog;
use App\Models\UserWallet;
use Carbon\Carbon;

class CashTransfer
{
    /**
     * 配置信息
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * 数据库连接
     * @var ConnectionInterface
     */
    protected $connection;

    public function __construct(SettingsRepository $settings, ConnectionInterface $connection)
    {
        $this->settings = $settings;
        $this->connection = $connection;
    }

    public function handle(Cash $event)
    {
        switch ($event->transfer_type) {
            case GatewayConfig::WECAHT_TRANSFER://微信企业付款
                $result = $this->wecahtTransfer($event);
                break;
            default:
                break;
        }
    }

    /**
     * 微信企业付
     * @param  Cash   $event 事件参数
     */
    public function wecahtTransfer(Cash $event)
    {
        //获取用户openid
        $user_id = $event->cash_record->user_id;
        $user_wecaht = UserWechat::find($user_id);
        $openid = $user_wecaht->openid;

        //获取微信配置
        $config = $this->settings->tag('wxpay');
        //微信证书
        $config['cert_path'] = storage_path().'/cert/apiclient_cert.pem';
        $config['key_path'] = storage_path().'/cert/apiclient_key.pem';
        //微信金额单位为分
        $cash_amount = bcmul((string) $event->cash_record->cash_actual_amount, '100', 0);
        $cash_sn = $event->cash_record->cash_sn;
        $data = [
            'partner_trade_no' => $cash_sn,//商户订单号
            'openid' => $openid,//用户openid
            'amount' => $cash_amount,
            'desc' => '提现',//备注
            'spbill_create_ip' => $event->ip_address,
            'check_name' => 'NO_CHECK',//NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
            //'re_user_name' => '',//收款用户真实姓名
        ];
        //企业付款
        TransferTrade::transfer($event->transfer_type, $config, $data);
        $response = TransferTrade::getTransferRespone();
        if (TransferTrade::getTransferStatus()) {
            $data_result = [
                'trade_time' => Carbon::parse($response['payment_time'])->format('Y-m-d H:i:s'),//交易时间
                'payment_no' => $response['payment_no'],//交易号
                'cash_sn' => $response['partner_trade_no'],//商户交易号
            ];
            $this->transferSuccess($event->cash_record->id, $data_result);
        } else {
            $data_result = [
                'trade_time' => Carbon::now(),//交易时间
                'error_code' => $response['err_code'],//错误代码
                'error_message' => $response['err_code_des'],
            ];
            //失败时统一由后台手动回退
            $is_thaw = false;
            // if ($response['err_code'] == 'SYSTEMERROR') {
            //     //未明确的错误码,不做其他操作，后续人工检查
            //     $is_thaw = false;
            // }
            $this->transferFailure($event->cash_record->id, $data_result, $is_thaw);
        }
    }

    /**
     * 提现成功结果处理
     * @param  $cash_id 提现数据id
     * @param  array $data 结果数组
     */
    public function transferSuccess($cash_id, $data)
    {
        $user_wallet_cash = UserWalletCash::find($cash_id);
        $cash_apply_amount = $user_wallet_cash->cash_apply_amount;//提现申请金额
        $user_id = $user_wallet_cash->user_id;//提现用户

        if ($user_wallet_cash->cash_sn == $data['cash_sn']) {
            $user_wallet_cash->trade_time = $data['trade_time'];//交易时间
            $user_wallet_cash->trade_no = $data['payment_no'];//交易号
            $user_wallet_cash->error_code = '';
            $user_wallet_cash->error_message = '';
            $user_wallet_cash->cash_status = UserWalletCash::STATUS_PAID;//已打款
            $user_wallet_cash->save();
            //开始事务
            $this->connection->beginTransaction();
            try {
                //获取用户钱包
                $user_wallet = UserWallet::lockForUpdate()->find($user_id);
                //去除冻结金额
                $user_wallet->freeze_amount = $user_wallet->freeze_amount - $cash_apply_amount;
                $user_wallet->save();
                //冻结变动金额，为负数
                $change_freeze_amount = -$cash_apply_amount;
                //添加钱包明细
                $user_wallet_log = UserWalletLog::createWalletLog($user_id, 0, $change_freeze_amount, UserWalletLog::TYPE_CASH_SUCCESS, app('translator')->get('wallet.cash_success'));
                //提交事务
                $this->connection->commit();
                return true;
            } catch (Exception $e) {
                //回滚事务
                $this->connection->rollback();
                return false;
            }
        }
    }

    /**
     * 提现失败
     * @param  $cash_id 提现数据id
     * @param  array $data 结果数组
     * @param  $is_thaw 是否解冻提现金额
     */
    public function transferFailure($cash_id, $data, bool $is_thaw = false)
    {
        $user_wallet_cash = UserWalletCash::find($cash_id);
        $cash_apply_amount = $user_wallet_cash->cash_apply_amount;//提现申请金额
        $user_id = $user_wallet_cash->user_id;//提现用户

        $user_wallet_cash->trade_time = Carbon::now();//交易时间
        $user_wallet_cash->cash_status = UserWalletCash::STATUS_PAYMENT_FAILURE;//打款失败
        $user_wallet_cash->error_code = $data['error_code'];//错误代码
        $user_wallet_cash->error_message = $data['error_message'];//错误描述
        $user_wallet_cash->save();

        if ($is_thaw) {
            //开始事务
            $this->connection->beginTransaction();
            try {
                //获取用户钱包
                $user_wallet = UserWallet::lockForUpdate()->find($user_id);
                //返回冻结金额至用户钱包
                $user_wallet->freeze_amount = $user_wallet->freeze_amount - $cash_apply_amount;
                $user_wallet->available_amount = $user_wallet->available_amount + $cash_apply_amount;
                $user_wallet->save();

                //冻结变动金额，为负数数
                $change_freeze_amount = -$cash_apply_amount;
                //可用金额增加
                $change_available_amount = $cash_apply_amount;
                //添加钱包明细
                $user_wallet_log = UserWalletLog::createWalletLog($user_id, $change_available_amount, $change_freeze_amount, UserWalletLog::TYPE_CASH_THAW, app('translator')->get('wallet.cash_failure'));
                $user_wallet_cash->refresh();
                $user_wallet_cash->refunds_status = UserWalletCash::REFUNDS_STATUS_YES;
                $user_wallet_cash->save();

                $this->connection->commit();
                return true;
            } catch (Exception $e) {
                //回滚事务
                $this->connection->rollback();
                return false;
            }
        } else {
            //未明确的错误码,不做其他操作，后续人工检查
            return true;
        }
    }
}
