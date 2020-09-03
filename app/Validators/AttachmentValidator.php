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

use App\Models\Attachment;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Factory;

class AttachmentValidator extends AbstractValidator
{
    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Factory $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);

        $this->settings = $settings;
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        $typeName = $this->getTypeName();
        // 文件类型
        $mimes = Str::of($this->settings->get("support_{$typeName}_ext"))
            ->explode(',')
            ->unique();
        $mimes = $mimes->each(function ($value, $key) use ($mimes) {
            // 无论如何禁止上传 php 文件
            if ($value == 'php') {
                unset($mimes[$key]);
            }
        })->push('bin')->join(',');

        // 验证规则
        $rules =  [
            'type' => 'required|integer|between:0,4',
            'size' => 'bail|gt:0',
            'file' => ['bail', 'required', "mimes:{$mimes}"],
        ];

        // 文件大小
        if ($size = $this->settings->get('support_max_size', 'default', 0)) {
            $rules['file'][] = 'max:' . ($size * 1024);
        }

        return $rules;
    }

    protected function getMessages()
    {
        $typeName = $this->getTypeName();
        return [
            'file.mimes' => '文件类型错误，支持'.$this->settings->get("support_{$typeName}_ext"),
        ];
    }

    private function getTypeName()
    {
        $type = (int) Arr::get($this->data, 'type');
        $typeName = Arr::get(Attachment::$allowTypes, $type, head(Attachment::$allowTypes));
        if ($type == 4) {
            //消息类型的附件与图片相同
            $typeName = Arr::get(Attachment::$allowTypes, 1);
        }

        return $typeName;
    }
}
