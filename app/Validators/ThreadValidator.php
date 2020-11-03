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

namespace App\Validators;

use App\Rules\Captcha;
use Discuz\Foundation\AbstractValidator;

class ThreadValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'title' => 'required|max:150',
            'location' => 'max:100',
            'price' => [
                'sometimes',
                'regex:/^(0|[1-9]\d{0,5})(\.\d{1,2})?$/',   // decimal 10,2
            ],
            'free_words' => 'required_with:price|numeric|in:0,0.1,0.3,0.5,0.7,1',
            'attachment_price' => [
                'sometimes',
                'regex:/^(0|[1-9]\d{0,5})(\.\d{1,2})?$/',   // decimal 10,2
            ],
            'captcha' => [
                'sometimes',
                new Captcha,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        return [];
    }
}
