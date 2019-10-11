<?php
namespace App\Pay\WxPayApi\WxPayData;

use App\Pay\WxPayApi\WxPayConfigInterface;
use App\Pay\WxPayApi\WxPayException;



/**
 * 2015-06-29 修复签名问题
 **/
// require_once "WxPay.Config.Interface.php";
// require_once "WxPay.Exception.php";




/**
 *
 * 回调回包数据基类
 *
 **/
class WxPayNotifyResults extends WxPayResults
{
	/**
	 * 将xml转为array
	 *
	 * @param WxPayConfigInterface $config
	 * @param string $xml
	 *
	 * @return WxPayNotifyResults
	 * @throws WxPayException
	 */
	public static function Init($config, $xml)
	{
		$obj = new self();
		$obj->FromXml($xml);
		//失败则直接返回失败
		$obj->CheckSign($config);
		return $obj;
	}
}