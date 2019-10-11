<?php
namespace App\Pay\OpenPay\Config;

use App\Pay\WxPayApi\WxPayConfigInterface;


class WxPayConfig extends WxPayConfigInterface {
	
	public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath) {
	}
	
	public function GetKey() {
		return 'sajhuHDAAhkH134118DH1O1OR31O12e1';
	}
	
	public function GetAppId() {
		return 'wx24ef8273fde3334e';
	}
	
	public function GetNotifyUrl() {
		return 'https://2020.comsenz-service.com/api/pay/notify';
	}
	
	public function GetReportLevenl() {
		return '';
	}
	
	public function GetMerchantId() {
		return '1515287121';
	}
	
	/**
	 * 签名类型，默认为MD5，支持HMAC-SHA256和MD5。
	 */
	public function GetSignType() {
		return 'MD5';
	}
	
	
	public function GetProxy(&$proxyHost, &$proxyPort) {
		$proxyHost = '0.0.0.0';
		$proxyPort = 0;
	}
	
	public function GetAppSecret() {
		return '';
	}
	
	
}

