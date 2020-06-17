<?php
/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Exception;

use Exception;

/**
 * Class BlockParseException
 * @package App\BlockEditor\Exception
 */

class BlockParseException  extends Exception implements EditorExceptionInterface
{
    public function __construct($message = '', $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
