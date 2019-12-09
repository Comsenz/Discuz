<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: WalletExceptionHandler.php xxx 2019-10-23 11:10 zhouzhou $
 */

namespace App\Api\Exceptions;

use App\Exceptions\OrderException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class OrderExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof OrderException;
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
            'message' => app('translator')->get('order.'. $e->getMessage()),
        ];
        return new ResponseBag($status, [$error]);
    }
}
