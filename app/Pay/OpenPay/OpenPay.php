<?php
namespace App\Pay\OpenPay;

use App\Pay\OpenPay\PayQrcode\WxPayQrcode;

class OpenPay
{
//	const VERSION = '0.0.01';

	protected $platfPrefixList = [
			'1' => 'Wx', // 微信
			'2' => 'Ali', // 支付宝
		];
	
	/**
	 * 生成支付二维码
	 */
	public function payQrcode($platform, $scene, $product_type, $product_id, $print=true)
	{
		
		$clsName = 'xubin\\openpay\\PayQrcode\\' . $this->platfPrefixList[$platform] . 'PayQrcode';
		return (new $clsName)->run($scene, $product_type, $product_id, $print);
		
	}
	
	
	/**
	 * 生成支付订单
	 */
	public function createOrder()
	{
		
	}
	
	/**
	 * 支付完成后的回调
	 */
	public function resultCallback()
	{
		
	}
	
	/**
	 * 查询支付订单
	 */
	public function queryOrder()
	{
		;
	}
	
}

