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
        // type：0 附件 1 图片 2 音频 3 视频
        $type = (int) Arr::get($this->data, 'type');
        $typeName = Arr::get(Attachment::$type, $type, head(Attachment::$type));

        // 文件类型
        $mimes = Str::of($this->settings->get("support_{$typeName}_ext"))
            ->explode(',')
            ->unique()
            ->filter(function ($value) {
                // 无论如何禁止上传 php 文件
                return $value !== 'php';
            })
            ->join(',');

        // 验证规则
        $rules =  [
            'type' => 'required|integer|between:0,3',
            'file' => ['required', "mimes:{$mimes}"],
        ];

        // 文件大小
        if ($size = $this->settings->get('support_max_size', 'default', 0)) {
            $rules['file'][] = 'max:' . ($size * 1024 * 1024);
        }

        return $rules;
    }
}
