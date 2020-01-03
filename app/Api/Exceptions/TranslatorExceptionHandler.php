<?php

namespace App\Api\Exceptions;

use Discuz\Locale\AbstractLocaleException;
use Exception;
use App\Exceptions\TranslatorException;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class TranslatorExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof TranslatorException;
    }

    /**
     * @param Exception $e
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        $errors = [
            [
                'status' => $e->getCode(),
                'code' => $e->getMessage() ?: 'unknown_error',
            ]
        ];
        if (!empty($e->getDetail())) {
            $errors = array_merge($errors[0], ['detail' => $e->getDetail()]);
        }

        return new ResponseBag(500, [$errors]);
    }
}
