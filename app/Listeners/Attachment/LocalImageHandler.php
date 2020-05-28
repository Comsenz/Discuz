<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        /**
         * TODO: is_gallery is_sound 需要整合为 type 字段
         *
         * type：0 附件 1 图片 2 音频 3 视频
         */
        $isGallery = (bool) Arr::get($request->getParsedBody(), 'isGallery', false);
        $type = $isGallery ? 1 : (int) Arr::get($request->getParsedBody(), 'type', 0);

        $this->data = array_merge($request->getParsedBody(), ['type' => $type]);
    }

    public function handle(Uploaded $event)
    {
        $uploader = $event->uploader;

        // 帖子图片处理
        if ((int) Arr::get($this->data, 'type') === Attachment::TYPE_OF_IMAGE && !$uploader->isRemote()) {
            $image = (new ImageManager)->make(
                storage_path('app/' . $uploader->file->hashName($uploader->getPath()))
            );

            $thumbPath = Str::replaceLast($image->filename, $image->filename . '_thumb', $image->basePath());
            $blurPath = Str::replaceLast($image->filename, md5($image->filename) . '_blur', $image->basePath());

            // 生成缩略图
            $image->resize(Attachment::FIX_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();     // 保持纵横比
                $constraint->upsize();          // 避免文件变大
            })->save($thumbPath);

            // 生成模糊图
            $image->blur(75)->save($blurPath);
        }
    }
}
