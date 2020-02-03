<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exceptions;

use Exception;

class OrderException extends Exception
{
    public function __construct($message = '', $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
