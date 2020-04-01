<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use App\Exceptions\NoUserException;
use App\Exceptions\QrcodeImgException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class QrcodeImgExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param \Exception $e
     *
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof QrcodeImgException;
    }

    /**
     * Handle the provided exception.
     *
     * @param \Exception $e
     *
     * @return \Tobscure\JsonApi\Exception\Handler\ResponseBag
     */
    public function handle(Exception $e)
    {
        // TODO: Implement handle() method.
        $status = $e->getCode();
        $error  = [
            'status'  => $status,
            'code'   =>'qrcode_error',
            'detail' => $e->getMessage(),
        ];

        return new ResponseBag($status, [$error]);
    }
}
