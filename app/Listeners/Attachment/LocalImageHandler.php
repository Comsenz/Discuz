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

use App\Events\Attachment\Uploaded;
use App\Models\Attachment;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;

class LocalImageHandler
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var ImageManager
     */
    public $image;

    /**
     * @param ServerRequestInterface $request
     * @param ImageManager $image
     */
    public function __construct(ServerRequestInterface $request, ImageManager $image)
    {
        $this->data = $request->getParsedBody();
        $this->image = $image;
    }

    /**
     * @param Uploaded $event
     */
    public function handle(Uploaded $event)
    {
        $uploader = $event->uploader;

        // 非帖子图片 或 远程图片 不处理
        if ((int) Arr::get($this->data, 'type') !== Attachment::TYPE_OF_IMAGE || $uploader->isRemote()) {
            return;
        }

        // 原图
        $image = $this->image->make(
            storage_path('app/' . $uploader->getFullPath())
        );

        // 缩略图及高斯模糊图存储路径
        $thumbPath = Str::replaceLast($image->filename, $image->filename . '_thumb', $image->basePath());
        $blurPath = Str::replaceLast($image->filename, md5($image->filename) . '_blur', $image->basePath());

        // 生成缩略图
        $image->resize(Attachment::FIX_WIDTH, Attachment::FIX_WIDTH, function ($constraint) {
            $constraint->aspectRatio();     // 保持纵横比
            $constraint->upsize();          // 避免文件变大
        })->save($thumbPath);

        // 生成模糊图
        $image->blur(80)->save($blurPath);
    }
}
