<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: PayOrder.php XXX 2019-10-17 10:00 zhouzhou $
 */

namespace App\Commands\Trade;

use App\Models\Order;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use App\Trade\Config\GatewayConfig;
use App\Trade\PayTrade;
use App\Settings\SettingsRepository;

class PayOrder
{
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
     * 初始化命令参数
     * @param string   $order_sn   订单编号.
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($order_sn, $actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->order_sn = $order_sn;
    }

    /**
     * 执行命令
     * @return Order
     * @throws Exception
     */
    public function handle(Validator $validator)
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
        	'payment_type' => 'required'
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $order_info = Order::where('order_sn', $this->order_sn)->first();
       
        $payment_params = '';
        $payment_type = (int)$this->data->get('payment_type');
        if (!empty($order_info)) {
            $order_info->body = '收款';
            // 支付参数
            $order_info->payment_params = $this->paymentParams($order_info->toArray(), $payment_type);
        }
       
        // 返回数据对象
        return $order_info;
    }

    /**
     * 获取支付参数
     * @param  int $payment_type 支付方式
     * @return array  支付参数数组
     */
    public function paymentParams($order_info, $payment_type)
    {
    	if (empty($order_info)) {
    		return [];
    	}
        // $wechat_payment_config = $this->app->make(SettingsRepository::class)->get('wechat_payment_config');
        // print_r($wechat_payment_config);
        // exit;
    	$config = [
    		'app_id' => '',
    		'mch_id' => '',
    		'api_key' => '',
            'notify_url' => '',
    	];
        $extra = [];//可选参数
        $pay_gateway = '';//付款通道及方式
    	switch ($payment_type) {
    		case '10': //微信扫码支付
                $pay_gateway = GatewayConfig::WECAHT_PAY_NATIVE;
    			break;
            case '11': //微信h5支付
                $pay_gateway = GatewayConfig::WECAHT_PAY_WAP;
                break;
            case '12': //微信网页、公众号、小程序支付网关
                $pay_gateway = GatewayConfig::WECAHT_PAY_JS;
                $extra = [
                    'openid' => 'opBsM00ZzlDhl7cEVonf_MD01VK4',//$this->$actor->openid;
                ];
                break;
    		default:
                return [];
    			break;
    	}
        return PayTrade::pay($order_info, $pay_gateway, $config, $extra);//生成支付参数
    }

}