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
