<?php
namespace xubin\openpay\PayQrcode;

use xubin\phpqrcode\QRFactory;
use xubin\wxpayapi\WxPayApi;
use xubin\openpay\Config\WxPayConfig;
use xubin\wxpayapi\WxPayData\WxPayBizPayUrl;

class WxPayQrcode implements PayQrcodeInterface {
	
	
	public function run($scene, $product_type, $product_id, $print_img=true) {
		
		$objPayApi = new WxPayApi();
		$objPayConf = new WxPayConfig();
		$objPayInput = new WxPayBizPayUrl();
		$objPayInput->SetProduct_id($scene . '-' . $product_type . '-' . $product_id);
		$urlParams = $objPayApi->bizpayurl($objPayConf, $objPayInput);
		
		$wxUrl = 'weixin://wxpay/bizpayurl?appid=%s&mch_id=%s&nonce_str=%s&product_id=%s&time_stamp=%s&sign=%s';
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

