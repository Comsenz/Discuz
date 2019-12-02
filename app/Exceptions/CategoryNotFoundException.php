<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CategoryNotFoundException.php xxx 2019-12-02 11:10:00 LiuDongdong $
 */

namespace App\Exceptions;

use Exception;

class CategoryNotFoundException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
