<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use App\Exceptions\TradeErrorException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class TradeErrorExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof TradeErrorException;
    }

    /**
     * Handle the provided exception.
     *
     * @param  $e
     *
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        $code = $e->getMessage();
        $language = app('translator')->get('trade.'. $code);
        if (app('translator')->get($language) == 'trade.'. $code) {
            $language = $e->getMessage();
        }
        $status = $e->getCode();
        $error  = [
            'status'  => $status,
            'code'    => 'trade_error',
            'detail' => $language,
        ];
        return new ResponseBag($status, [$error]);
    }
}
