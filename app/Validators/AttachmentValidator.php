<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
        $type = (int) Arr::get($this->data, 'type');
        $typeName = Arr::get(Attachment::$allowTypes, $type, head(Attachment::$allowTypes));
        if ($type == 4) {
            //消息类型的附件与图片相同
            $typeName = Arr::get(Attachment::$allowTypes, 1);
        }

        // 文件类型
        $mimes = Str::of($this->settings->get("support_{$typeName}_ext"))
            ->explode(',')
            ->unique();
        $mimes = $mimes->each(function ($value, $key) use ($mimes) {
            if ($value == 'mp3') {
                $mimes[$key] = 'mpga';
            }
            if ($value == 'm4a') {
                $mimes[$key] = 'x-m4a';
            }
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
            $rules['file'][] = 'max:' . ($size * 1024 * 1024);
        }

        return $rules;
    }
}
