<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade;

use App\Exceptions\TradeErrorException;
use App\Models\Order;
use App\Models\User;
use App\Settings\SettingsRepository;
use App\Trade\Config\GatewayConfig;
use App\Trade\PayTrade;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Routing\UrlGenerator;
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
     * @return Order
     * @throws PermissionDeniedException
     * @throws TradeErrorException
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function handle(Validator $validator, SettingsRepository $setting, UrlGenerator $url)
    {
        $this->assertCan($this->actor, 'trade.pay.order');

        $this->setting = $setting;
        $this->url     = $url;

        // 使用钱包支付时，检查是否设置支付密码
        if (
            $this->data->get('payment_type') == GatewayConfig::WALLET_PAY
            && empty($this->actor->pay_password)
        ) {
            throw new \Exception('uninitialized_pay_password');
        }

        $validator_info = $validator->make($this->data->toArray(), [
            'payment_type' => 'required',
            'pay_password' => [
                'sometimes',
                'required_if:payment_type,20',
                function ($attribute, $value, $fail) {
                    // 使用钱包支付时验证密码
                    if (
                        $this->data->get('payment_type') == GatewayConfig::WALLET_PAY
                        && ! $this->actor->checkWalletPayPassword($value)
                    ) {
                        $fail('支付密码错误');
                    }
                }
            ],
        ], [
            'pay_password.required_if' => '使用钱包支付时，请输入支付密码。',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $order_info = Order::where('user_id', $this->actor->id)
            ->where('order_sn', $this->order_sn)
            ->where('status', Order::ORDER_STATUS_PENDING)
            ->firstOrFail();

        $this->payment_type = (int) $this->data->get('payment_type');
        switch ($order_info->type) {
            case Order::ORDER_TYPE_REGISTER:
                $order_info->body = '注册';
                break;
            case Order::ORDER_TYPE_REWARD:
                $order_info->body = '打赏';
                break;
            case Order::ORDER_TYPE_THREAD:
                $order_info->body = '付费主题';
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

        // TODO: 并不是每种方式都需要微信支付配置
        $config      = $this->setting->tag('wxpay'); //配置信息
        switch ($this->payment_type) {
            case '10': //微信扫码支付
                $pay_gateway          = GatewayConfig::WECAHT_PAY_NATIVE;
                $config['notify_url'] = $this->url->to('/api/trade/notify/wechat');
                break;
            case '11': //微信h5支付
                $config['notify_url'] = $this->url->to('/api/trade/notify/wechat');
                $pay_gateway          = GatewayConfig::WECAHT_PAY_WAP;
                $extra = [
                    'h5_info' => [
                        'type' => 'Wap',
                        'wap_url' => '',
                        'wap_name' => ''
                    ]
                ];
                break;
            case '12': //微信网页、公众号、小程序支付网关
                $config['notify_url'] = $this->url->to('/api/trade/notify/wechat');
                $pay_gateway          = GatewayConfig::WECAHT_PAY_JS;
                //获取用户openid
                $extra                = [
                    'openid' => $this->actor->wechat->mp_openid,
                ];
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
