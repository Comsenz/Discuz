<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use App\Censor\CensorNotPassedException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class CensorNotPassedExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function manages(Exception $e)
    {
        return $e instanceof CensorNotPassedException;
    }

    /**
     * @inheritDoc
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
