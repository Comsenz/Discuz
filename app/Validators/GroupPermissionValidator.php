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

use Discuz\Foundation\AbstractValidator;

class GroupPermissionValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'permission' => [
                'required',
                'regex:/^[a-zA-Z.]+$/i'
            ],
        ];
    }

    protected function getMessages()
    {
        return [
            'permission.required' => '不能为空',
            'permission.regex' => '权限名称不符合规则'
        ];
    }
}
