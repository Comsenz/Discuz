<?php
namespace xubin\wxpayapi\WxPayData;

use xubin\wxpayapi\WxPayConfigInterface;


/**
 * 2015-06-29 修复签名问题
 **/
// require_once "WxPay.Config.Interface.php";
// require_once "WxPay.Exception.php";


/**
 *
 * 只使用md5算法进行签名， 不管配置的是什么签名方式，都只支持md5签名方式
 *
 **/
class WxPayDataBaseSignMd5 extends WxPayDataBase
{
	/**
	 * 生成签名 - 重写该方法
	 * @param WxPayConfigInterface $config  配置对象
	 * @param bool $needSignType  是否需要补signtype
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function MakeSign($config, $needSignType = false)
	{
		if($needSignType) {
			$this->SetSignType($config->GetSignType());
		}
		//签名步骤一：按字典序排序参数
		ksort($this->values);
		$string = $this->ToUrlParams();
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$config->GetKey();
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
}