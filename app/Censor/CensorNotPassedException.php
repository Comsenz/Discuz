<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CensorNotPassedException.php xxx 2019-10-18 16:09:00 LiuDongdong $
 */

namespace App\Censor;

use Exception;

class CensorNotPassedException extends Exception
{
    public function __construct($message = '', $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
