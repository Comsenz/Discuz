<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Exceptions;

use Exception;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class SmsExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof NoGatewayAvailableException;
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
        //由于该异常可能未返回相关数据，定义默认显示异常
        $defaultErrors = [
            [
                'gateway' => '',
                'status' => $e->getCode(),
                'exception' => $e->getMessage()
            ]
        ];
        $errors = $this->buildErrors($e->getResults());

        $errors = !empty($errors) ? $errors : $defaultErrors;

        return new ResponseBag(500, $errors);
    }

    private function buildErrors(array $messages)
    {
        return array_map(function ($gateway, $result) {
            return [
                'gateway' => $gateway,
                'status' => $result['status'],
                'detail' => [$result['exception']->getMessage()],
            ];
        }, array_keys($messages), $messages);
    }
}
