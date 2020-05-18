<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade;

use App\Exceptions\TradeErrorException;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWalletFailLogs;
use App\Repositories\UserWalletFailLogsRepository;
use App\Settings\SettingsRepository;
use App\Trade\Config\GatewayConfig;
use App\Trade\PayTrade;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class PayOrder
{
    use AssertPermissionTrait;

    /**
     * 订单编号
     *
     * @var string
     */
    public $order_sn;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 配置
     *
     * @var SettingsRepository
     */
    public $setting;

    /**
     * 链接生器
     *
     * @var UrlGenerator
     */
    public $url;

    /**
     * 支付类型
     *
     * @var string
     */
    protected $payment_type;

    /**
     * @var UserWalletFailLogsRepository
     */
    protected $userWalletFailLogs;

    /**
     * 初始化命令参数
     * @param string $order_sn 订单编号.
     * @param User $actor 执行操作的用户.
     * @param Collection $data 请求的数据.
     */
    public function __construct($order_sn, User $actor, Collection $data)
    {
        $this->actor    = $actor;
        $this->data     = $data;
        $this->order_sn = $order_sn;
    }

    /**
     * 执行命令
     * @param Validator $validator
     * @param SettingsRepository $setting
     * @param UrlGenerator $url
     * @param UserWalletFailLogsRepository $userWalletFailLogs
     * @return Order
     * @throws PermissionDeniedException
     * @throws TradeErrorException
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(Validator $validator, SettingsRepository $setting, UrlGenerator $url, UserWalletFailLogsRepository $userWalletFailLogs)
    {
        $this->assertCan($this->actor, 'trade.pay.order');

        $this->setting = $setting;
        $this->url     = $url;
        $this->userWalletFailLogs = $userWalletFailLogs;

        $this->data = collect(Arr::get($this->data, 'data.attributes'));
        // 使用钱包支付时，检查是否设置支付密码
        if (
            $this->data->get('payment_type') == 20
            && empty($this->actor->pay_password)
        ) {
            throw new \Exception('uninitialized_pay_password');
        }

        // 验证错误次数
        $failCount = $this->userWalletFailLogs->getCountByUserId($this->actor->id);
        if (
            $this->data->get('payment_type') == 20
            && $failCount > UserWalletFailLogs::TOPLIMIT
        ) {
            throw new \Exception('pay_password_failures_times_toplimit');
        }

        $validator_info = $validator->make($this->data->toArray(), [
            'payment_type' => 'required',
            'pay_password' => [
                'sometimes',
                'required_if:payment_type,20',
                function ($attribute, $value, $fail) {
                    // 使用钱包支付时验证密码
                    if (
                        $this->data->get('payment_type') == 20
                        && ! $this->actor->checkWalletPayPassword($value)
                    ) {
                        //记录钱包密码错误日志
                        $request = app('request');
                        UserWalletFailLogs::build(ip($request->getServerParams()), $this->actor->id);

                        $fail(trans('trade.wallet_pay_password_error'));
                    }
                }
            ],
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        // 正确后清除错误记录
        if ($failCount > 0) {
            UserWalletFailLogs::deleteAll($this->actor->id);
        }

        $order_info = Order::where('user_id', $this->actor->id)
            ->where('order_sn', $this->order_sn)
            ->where('status', Order::ORDER_STATUS_PENDING)
            ->firstOrFail();

        $this->payment_type = (int) $this->data->get('payment_type');
        switch ($order_info->type) {
            case Order::ORDER_TYPE_REGISTER:
                $order_info->body = trans('order.order_type_register');
                break;
            case Order::ORDER_TYPE_REWARD:
                $order_info->body = trans('order.order_type_reward');
                break;
            case Order::ORDER_TYPE_THREAD:
                $order_info->body = trans('order.order_type_thread');
                break;
            case Order::ORDER_TYPE_GROUP:
                $order_info->body = trans('order.order_type_group');
                break;
            default:
                $order_info->body = '';
                break;
        }

        // 支付参数
        $order_info->payment_params = $this->paymentParams($order_info->toArray());
        if (!empty($order_info->payment_params)) {
            Order::where('order_sn', $this->order_sn)->update(['payment_type' => $this->payment_type]);
        }
        // 返回数据对象
        return $order_info;
    }

    /**
     * 获取支付参数
     * @param array $order_info
     * @return array  $order_info 支付参数数组
     * @throws TradeErrorException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function paymentParams($order_info)
    {
        if (empty($order_info)) {
            return [];
        }
        $extra       = []; //可选参数
        $pay_gateway = ''; //付款通道及方式
        $config      = []; //配置信息
        switch ($this->payment_type) {
            case '10': //微信扫码支付
            case '11': //微信h5支付
            case '12': //微信网页、公众号
            case '13': //微信小程序支付
                $config = $this->setting->tag('wxpay'); //配置信息
                $config['notify_url'] = $this->url->to('/api/trade/notify/wechat');
                switch ($this->payment_type) {
                    case '10': //微信扫码支付
                        $pay_gateway          = GatewayConfig::WECAHT_PAY_NATIVE;
                        break;
                    case '11': //微信h5支付
                        $pay_gateway          = GatewayConfig::WECAHT_PAY_WAP;
                        $extra = [
                            'h5_info' => [
                                'type' => 'Wap',
                                'wap_url' => '',
                                'wap_name' => ''
                            ]
                        ];
                        break;
                    case '12': //微信网页、公众号
                        $pay_gateway          = GatewayConfig::WECAHT_PAY_JS;
                        //获取用户openid
                        $extra                = [
                            'openid' => $this->actor->wechat->mp_openid,
                        ];
                        break;
                    case '13': //小程序支付
                        $config['app_id']     = $this->setting->get('miniprogram_app_id', 'wx_miniprogram');//小程序openid
                        $pay_gateway          = GatewayConfig::WECAHT_PAY_JS;
                        //获取用户openid： min_openid
                        $extra                = [
                            'openid' => $this->actor->wechat->min_openid,
                        ];
                        break;
                }
                break;
            case '20': // 用户钱包支付
                $pay_gateway = GatewayConfig::WALLET_PAY;
                break;
            default:
                throw new TradeErrorException('payment_method_invalid', 500);
                break;
        }
        return PayTrade::pay($order_info, $pay_gateway, $config, $extra); //生成支付参数
    }
}
