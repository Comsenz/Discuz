<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use Discuz\Http\Exception\RouteNotFoundException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class NoExceptionHandler implements ExceptionHandlerInterface
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
        // TODO: Implement manages() method.
        return $e instanceof RouteNotFoundException;
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
        $status = 404;
        $error = [
            'status' => (string) $status,
            'code' => 'route_not_found'
        ];

        return new ResponseBag($status, [$error]);
    }
}
