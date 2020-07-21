<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
