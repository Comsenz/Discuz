<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class ModelNotFoundExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof ModelNotFoundException;
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
        $status = 404;
        $error = [
            'status' => (string) $status,
            'code' => 'model_not_found',
        ];

        return new ResponseBag($status, [$error]);
    }
}
