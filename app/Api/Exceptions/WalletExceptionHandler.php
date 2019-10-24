<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: WalletExceptionHandler.php xxx 2019-10-23 11:10 zhouzhou $
 */

namespace App\Api\Exceptions;

use App\Exceptions\ErrorException;
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
        return $e instanceof ErrorException;
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
            'status'  => (string) $e->getCode(),
            'code'    => 'wallet_error',
            'message' => $e->getMessage(),
        ];
        return new ResponseBag($status, [$error]);
    }
}
