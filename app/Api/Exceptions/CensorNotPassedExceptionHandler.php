<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CensorNotPassedExceptionHandler.php xxx 2019-10-18 16:13:00 LiuDongdong $
 */

namespace App\Api\Exceptions;

use Exception;
use Discuz\Censor\CensorNotPassedException;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class CensorNotPassedExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param Exception $e
     *
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof CensorNotPassedException;
    }

    /**
     * Handle the provided exception.
     *
     * @param Exception $e
     *
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        $status = 500;
        $error = [
            'status' => (string) $status,
            'code' => 'censor_not_passed',
        ];

        return new ResponseBag($status, [$error]);
    }
}
