<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->data = $request->getParsedBody();
    }

    public function handle(Uploading $event)
    {
        // 帖子图片处理
        if ((int) Arr::get($this->data, 'type') === Attachment::TYPE_OF_IMAGE && extension_loaded('exif')) {
            (new ImageManager)->make($event->file)->orientate()->save();
        }
    }
}
