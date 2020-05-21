<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Trade;

use App\Api\Controller\Trade\Notify\WalletNotifyController;
use App\Models\User;
use App\Trade\Config\GatewayConfig;
use Discuz\Api\Client;
use Endroid\QrCode\QrCode;
use Illuminate\Contracts\Container\BindingResolutionException;
use Omnipay\Omnipay;
use Illuminate\Support\Arr;
use App\Exceptions\TradeErrorException;

class PayTrade
{
    /**
     * 支付
     * @param array $order_info 订单信息
     * @param string $payment_type 支付方式
     * @param array $config 支付配置信息
     * @param mixed $extra 其他参数
     * @return array                支付参数
     * @throws TradeErrorException
     * @throws BindingResolutionException
     */
    public static function pay($order_info, $payment_type, $config, $extra = [])
    {
        $payment_params = []; //返回参数
        switch ($payment_type) {
            case GatewayConfig::WECAHT_PAY_NATIVE: //扫码支付
            case GatewayConfig::WECAHT_PAY_WAP: //h5支付
            case GatewayConfig::WECAHT_PAY_JS: //微信网页、公众号、小程序支付网关
                $payment_params = self::wechatPay($order_info, $payment_type, $config, $extra);
                break;
            case GatewayConfig::WALLET_PAY: // 用户钱包支付
                $payment_params = self::walletPay($order_info);
                break;
            default:
                break;
        }
        return $payment_params;
    }

    /**
     * @param $order_info
     * @return array
     * @throws BindingResolutionException
     */
    private static function walletPay($order_info)
    {
        // 余额是否充足
        $user = User::find($order_info['user_id']);
        if ($user->userWallet->available_amount < $order_info['amount']) {
            return [
                'wallet_pay' => [
                    'result' => 'fail',
                    'message' => trans('wallet.available_amount_error'),
                ]
            ];
        }

        // 支付成功后的回调处理
        $apiClient = app()->make(Client::class);

        $response = $apiClient->send(WalletNotifyController::class, $user, [], $order_info);

        return json_decode($response->getBody(), true);
    }

    /**
     * 微信支付
     * @param array $order_info 订单信息
     * @param string $payment_type 支付方式
     * @param array $config 支付配置信息
     * @param mixed $extra 其他参数
     * @return array                支付参数
     * @throws TradeErrorException
     */
    private static function wechatPay($order_info, $payment_type, $config, $extra)
    {
        $gateway = Omnipay::create($payment_type);

        $gateway->setAppId(Arr::get($config, 'app_id'));
        $gateway->setMchId(Arr::get($config, 'mch_id'));
        $gateway->setApiKey(Arr::get($config, 'api_key'));
        $gateway->setNotifyUrl(Arr::get($config, 'notify_url'));

        $request = app('request');

        //订单信息
        $order = [
            'body'             => $order_info['body'],
            'out_trade_no'     => $order_info['payment_sn'],
            'total_fee'        => bcmul((string) $order_info['amount'], '100', 0),
            'spbill_create_ip' => ip($request->getServerParams()),
            'fee_type'         => 'CNY',
        ];

        //WECAHT_PAY_JS支付时需要填写open_id
        if (isset($extra['openid'])) {
            $order['openid'] = $extra['openid'];
        }
        if (isset($extra['time_expire'])) {
            $order['time_expire'] = $extra['time_expire'];
        }

        if (isset($extra['h5_info'])) {
            $order['h5_info'] = $extra['h5_info'];
        }
        $request  = $gateway->purchase($order);
        $response = $request->send();

        $result   = [];
        if ($response->isSuccessful()) {
            switch ($payment_type) {
                case GatewayConfig::WECAHT_PAY_NATIVE:
                    $qr_link                 = $response->getCodeUrl();
                    $qrCode                  = new QrCode($qr_link);
                    $result['wechat_qrcode'] = 'data:image/png;base64,' . base64_encode($qrCode->writeString());
                    break;
                case GatewayConfig::WECAHT_PAY_WAP:
                    $h5_link                  = $response->getMwebUrl();
                    $result['wechat_h5_link'] = $h5_link;
                    break;
                case GatewayConfig::WECAHT_PAY_JS:
                    $js_data             = $response->getJsOrderData();
                    $result['wechat_js'] = $js_data;
                    break;
                default:
                    break;
            }
        } else {
            $message = $response->getData();
            $log = app('payLog');
            try {
                $log->info('WeiChat Pay Response: ', $message);
            } catch (\Exception $e) {
                goto todo;
            }

            todo:
            $message = isset($message['err_code_des']) ? $message['err_code_des'] : $message['return_msg'];
            throw new TradeErrorException($message, 500);
        }
        return $result;
    }
}
