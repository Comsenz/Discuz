<?php
namespace App\Pay\OpenPay\PayQrcode;

use App\Pay\WxPayApi\WxPayApi;
use App\Pay\WxPayApi\WxPayData\WxPayBizPayUrl;
use App\Pay\PhpQrCode\QRFactory;
use App\Pay\OpenPay\Config\WxPayConfig;

class WxPayQrcode implements PayQrcodeInterface {
	
	
	public function run($scene, $product_type, $product_id, $print_img=true) {
		
// 		$notify = new NativePay();
// 		$url1 = $notify->GetPrePayUrl("123456789");
		
		$objPayApi = new WxPayApi();
		$objPayConf = new WxPayConfig();
		$objPayInput = new WxPayBizPayUrl();
		$objPayInput->SetProduct_id($scene . '-' . $product_type . '-' . $product_id);
		$urlParams = $objPayApi->bizpayurl($objPayConf, $objPayInput);
		
		$wxUrl = 'weixin://wxpay/bizpayurl?appid=%s&mchid=%s&noncestr=%s&productid=%s&timestamp=%s&sign=%s';
		$sign = $urlParams['sign'];
		$appid = $urlParams['appid'];
		$mch_id = $urlParams['mch_id'];
		$pdt_id = $urlParams['product_id'];
		$time = $urlParams['time_stamp'];
		$nonce_str = $urlParams['nonce_str'];
		$wxUrl = sprintf($wxUrl, $appid, $mch_id, $nonce_str, $pdt_id, $time, $sign);
		
		return QRFactory::createImg($wxUrl, 2, 'H', $print_img);
// 		echo $wxUrl;
	}
	
	
}

