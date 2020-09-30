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

namespace App\Censor;

use Discuz\Locale\AbstractLocaleException;
use Exception;

class CensorNotPassedException extends AbstractLocaleException
{
    /**
     * CensorNotPassedException constructor.
     * @param string $message
     * @param array $detail
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = '', array $detail = [], $code = 500, Exception $previous = null)
    {
        $this->message = $message;

        $this->code = $code;

        $this->handle(func_get_args());

        parent::__construct($message, $code, $previous);
    }

    public function handle($args)
    {
        if (empty($args)) {
            return;
        }

        // set detail array
        $this->detail = $args;
    }

    /**
     * 错误数组
     *
     * @return array
     */
    public function getDetail(): array
    {
        return $this->detail;
    }

    /**
     * 错误信息
     *
     * @return string
     */
    public function getMessageInfo(): string
    {
        return $this->message;
    }
}
