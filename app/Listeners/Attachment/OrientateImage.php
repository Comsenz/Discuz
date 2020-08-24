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
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;

class OrientateImage
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
     * @param Uploading $event
     */
    public function handle(Uploading $event)
    {
        // 帖子图片自适应旋转
        if ((int) Arr::get($this->data, 'type') === Attachment::TYPE_OF_IMAGE && extension_loaded('exif')) {
            $this->image->make($event->file)->orientate()->save();
        }
    }
}
