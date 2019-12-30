<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use App\Exceptions\IntervalSmsSendException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class IntervalSmsSendHandler implements ExceptionHandlerInterface
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
        return $e instanceof IntervalSmsSendException;
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
        $errors = [
            [
                'status' => 500,
//                'code' => 100002,
                'code' => 'sms_interval',
            ]
        ];

        return new ResponseBag(500, $errors);
    }
}
