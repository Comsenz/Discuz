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
use Illuminate\Validation\Rule;

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
        $extensions = Str::of($this->settings->get("support_{$typeName}_ext"))
            ->explode(',')
            ->merge(['', 'bin']) // 无论如何允许上传的文件
            ->diff(['php'])      // 无论如何禁止上传的文件
            ->unique();

        // 验证规则
        $rules =  [
            'type' => 'required|integer|between:0,5',
            'file' => 'required',
            'size' => 'bail|gt:0',
            'ext' => [Rule::in($extensions)],
        ];

        // 文件大小
        if ($size = $this->settings->get('support_max_size', 'default', 0)) {
            $rules['size'] .= '|max:' . ($size * 1024 * 1024);
        }

        return $rules;
    }

    /**
     * @return array
     */
    protected function getMessages()
    {
        $typeName = $this->getTypeName();

        return [
            'ext.in' => '文件类型错误，支持' . $this->settings->get("support_{$typeName}_ext"),
        ];
    }

    /**
     * @return string
     */
    private function getTypeName()
    {
        $type = (int) Arr::get($this->data, 'type');

        // 消息 或 问答的图片按帖子图片类型验证
        if ($type === Attachment::TYPE_OF_DIALOG_MESSAGE || $type === Attachment::TYPE_OF_ANSWER) {
            $typeName = Arr::get(Attachment::$allowTypes, Attachment::TYPE_OF_IMAGE);
        } else {
            $typeName = Arr::get(Attachment::$allowTypes, $type, head(Attachment::$allowTypes));
        }

        return $typeName;
    }
}
