<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UploadException.php 28830 2019-10-08 10:54 chenkeke $
 */

namespace App\Exceptions;

use Exception;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class UploadException extends Exception
{
    public function __construct($message = null, $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
