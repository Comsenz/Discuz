<?php
namespace App\Pay\OpenPay\WxPay\Log;

use App\Pay\OpenPay\WxPay\Log\ILogHandler;

class CLogFileHandler implements ILogHandler
{
	private $handle = null;
	
	public function __construct($file = '')
	{
		$this->handle = fopen($file,'a');
	}
	
	public function write($msg)
	{
		fwrite($this->handle, $msg, 4096);
	}
	
	public function __destruct()
	{
		fclose($this->handle);
	}
}