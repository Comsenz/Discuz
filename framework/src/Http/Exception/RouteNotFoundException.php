<?php

/*
 * This file is part of Fine.
 *
 * (c) Leiyu <yleimm@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Id: RouteNotFoundException.php 2018/11/28 18:09
 */

namespace Discuz\Http\Exception;


use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct($message = null, $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
