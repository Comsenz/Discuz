<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade;

use App\Settings\SettingsRepository;
use App\Trade\Config\GatewayConfig;
use App\Trade\PayTrade;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Order;
use Discuz\Auth\AssertPermissionTrait;
use App\Exceptions\TradeErrorException;

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
     * @param string   $order_sn   订单编号.
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($order_sn, User $actor, Collection $data)
    {
        $this->actor    = $actor;
        $this->data     = $data;
        $this->order_sn = $order_sn;
    }

    /**
     * 执行命令
     * @return Order
     * @throws Exception
     */
    public function handle(Validator $validator, SettingsRepository $setting, UrlGenerator $url)
    {
        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'trade.pay.order');
        $this->setting = $setting;
        $this->url     = $url;
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'payment_type' => 'required'
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $order_info = Order::where('user_id', $this->actor->id)->where('order_sn', $this->order_sn)->where('status', Order::ORDER_STATUS_PENDING)->firstOrFail();

        $this->payment_type = (int) $this->data->get('payment_type');
        switch ($order_info->type) {
            case Order::ORDER_TYPE_REGISTER:
                $order_info->body = '注册';
                break;
            case Order::ORDER_TYPE_REWARD:
                $order_info->body = '打赏';
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
     * @return array  $order_info 支付参数数组
     */
    public function paymentParams($order_info)
    {
        if (empty($order_info)) {
            return [];
        }
        $extra       = []; //可选参数
        $pay_gateway = ''; //付款通道及方式
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
            default:
                throw new TradeErrorException('payment_method_invalid', 500);
                break;
        }
        return PayTrade::pay($order_info, $pay_gateway, $config, $extra); //生成支付参数
    }
}
