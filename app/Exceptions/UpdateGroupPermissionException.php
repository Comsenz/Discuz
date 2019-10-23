<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateGroupPermissionException.php 28830 2019-10-23 16:05 chenkeke $
 */

namespace App\Exceptions;


use Exception;

class UpdateGroupPermissionException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}