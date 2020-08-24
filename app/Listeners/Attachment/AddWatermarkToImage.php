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

namespace App\Listeners\Attachment;

use App\Events\Attachment\Uploading;
use App\Models\Attachment;
use App\Settings\SettingsRepository;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;

class AddWatermarkToImage
{
    /**
     * 水印图占原图的比例（默认 25%）
     */
    const RATIO = 0.25;

    /**
     * @var array
     */
    public $data;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @var ImageManager
     */
    public $image;

    /**
     * @var array
     */
    public $positions = [
        1 => 'top-left',
        2 => 'top',
        3 => 'top-right',
        4 => 'left',
        5 => 'center',
        6 => 'right',
        7 => 'bottom-left',
        8 => 'bottom',
        9 => 'bottom-right',
    ];

    /**
     * @param ServerRequestInterface $request
     * @param SettingsRepository $settings
     * @param ImageManager $image
     */
    public function __construct(ServerRequestInterface $request, SettingsRepository $settings, ImageManager $image)
    {
        $this->data = $request->getParsedBody();
        $this->settings = $settings;
        $this->image = $image;
    }

    /**
     * @param Uploading $event
     */
    public function handle(Uploading $event)
    {
        // 非帖子图片 或 未开启水印 不处理
        if (
            (int) Arr::get($this->data, 'type') !== Attachment::TYPE_OF_IMAGE
            || ! (bool) $this->settings->get('watermark', 'watermark')
        ) {
            return;
        }

        // 原图
        $image = $this->image->make($event->file);

        // 自定义水印图
        $watermarkImage = storage_path(
            'app/public/' . $this->settings->get('watermark_image', 'watermark')
        );

        // 默认水印图
        if (! is_file($watermarkImage)) {
            $watermarkImage = resource_path('images/watermark.png');

            // 默认水印图丢失 不处理
            if (! is_file($watermarkImage)) {
                return;
            }
        }

        // 水印图按原图百分比缩放
        $watermarkImage = $this->image->make($watermarkImage)
            ->resize($image->getWidth() * self::RATIO, $image->getHeight() * self::RATIO, function ($constraint) {
                $constraint->aspectRatio();     // 保持纵横比
                $constraint->upsize();          // 避免文件变大
            });

        // 水印位置
        $position = (int) $this->settings->get('position', 'watermark', 1);

        // the watermark image on x-axis of the current image.
        $x = (int) $this->settings->get('horizontal_spacing', 'watermark');

        // the watermark image on y-axis of the current image.
        $y = (int) $this->settings->get('vertical_spacing', 'watermark');

        $image->insert($watermarkImage, $this->positions[$position], $x, $y);

        $image->save();
    }
}
