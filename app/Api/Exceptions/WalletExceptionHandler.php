<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use App\Exceptions\WalletException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class WalletExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof WalletException;
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
        $status = $e->getCode();
        $error  = [
            'status'  => $status,
            'code'    => $e->getMessage(),
            'detail' => app('translator')->get('wallet.'. $e->getMessage()),
        ];
        return new ResponseBag($status, [$error]);
    }
}
