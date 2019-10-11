<?php
namespace App\Pay\OpenPay\PayQrcode;

interface PayQrcodeInterface {
	
	
	public function run($scene, $product_type, $product_id);
	
	
}


