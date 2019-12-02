<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CategoryNotFoundExceptionHandler.php xxx 2019-12-02 11:12:00 LiuDongdong $
 */

namespace App\Api\Exceptions;

use App\Exceptions\CategoryNotFoundException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class CategoryNotFoundExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function manages(Exception $e)
    {
        return $e instanceof CategoryNotFoundException;
    }

    /**
     * @inheritDoc
     */
    public function handle(Exception $e)
    {
        $status = 500;
        $error = [
            'status' => (string) $status,
            'code' => 'category_not_found',
        ];

        return new ResponseBag($status, [$error]);
    }
}
