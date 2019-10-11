<?php
namespace xubin\openpay\Config;

class OPayConfig
{
	const ORDER_EXPIRE_TIME = '2h';
	const DB_LIBRARY = 'illuminate/database';
	
	public function __get($name)
	{
		$name = strtoupper( $name );
		
		if ( isset(self::$name) ) {
			return self::$name;
		} else {
			return null;
		}
		
	}
	
	
}


