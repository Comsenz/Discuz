<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use Exception;
use App\BlockEditor\Exception\EditorExceptionInterface;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class EditorExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof EditorExceptionInterface;
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
        $code = $e->getMessage();
        $language = app('translator')->get('editor.' . $code);
        if (app('translator')->get($language) == 'editor.' . $code) {
            $language = $e->getMessage();
        }
        $status = $e->getCode();
        $error = [
            'status' => $status,
            'code' => 'editor_error',
            'detail' => $language,
        ];
        return new ResponseBag($status, [$error]);
    }
}
