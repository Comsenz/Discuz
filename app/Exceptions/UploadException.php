<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exceptions;

use Exception;

class UploadException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
