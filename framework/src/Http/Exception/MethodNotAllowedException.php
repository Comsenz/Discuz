<?php

/*
 * This file is part of Fine.
 *
 * (c) Leiyu <yleimm@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Id: MethodNotAllowedException.php 2018/11/28 18:09
 */

namespace Discuz\Http\Exception;


use Exception;

class MethodNotAllowedException extends Exception
{
    public function __construct($message = null, $code = 405, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
