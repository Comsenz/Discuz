<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Attachment;

use App\Events\Attachment\Uploading;
use App\Models\Attachment;
use App\Settings\SettingsRepository;
use Illuminate\Support\Arr;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;

class AddWatermarkToImage
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var SettingsRepository
     */
    public $settings;

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
     */
    public function __construct(ServerRequestInterface $request, SettingsRepository $settings)
    {
        /**
         * TODO: is_gallery is_sound 需要整合为 type 字段
         *
         * type：0 附件 1 图片 2 音频 3 视频
         */
        $isGallery = (bool) Arr::get($request->getParsedBody(), 'isGallery', false);
        $type = $isGallery ? 1 : (int) Arr::get($request->getParsedBody(), 'type', 0);

        $this->data = array_merge($request->getParsedBody(), ['type' => $type]);
        $this->settings = $settings;
    }

    public function handle(Uploading $event)
    {
        $file = $event->file;

        // 帖子图片处理
        if ((int) Arr::get($this->data, 'type') === Attachment::TYPE_OF_IMAGE) {
            $image = (new ImageManager)->make($file);

            $image = $this->watermark($image);

            $image->save();
        }
    }

    /**
     * 水印
     *
     * @param Image $image
     * @return Image
     */
    public function watermark(Image $image)
    {
        // 水印开关
        $watermark = (bool) $this->settings->get('watermark', 'watermark');

        // 自定义水印图
        $watermarkImage = storage_path(
            'app/public/' . $this->settings->get('watermark_image', 'watermark')
        );

        // 默认水印图
        if (! is_file($watermarkImage)) {
            $watermarkImage = resource_path('images/watermark.png');
        }

        if ($watermark) {
            // 水印位置
            $position = (int) $this->settings->get('position', 'watermark', 1);

            // the watermark image on x-axis of the current image.
            $x = (int) $this->settings->get('horizontal_spacing', 'watermark');

            // the watermark image on y-axis of the current image.
            $y = (int) $this->settings->get('vertical_spacing', 'watermark');

            $image->insert($watermarkImage, $this->positions[$position], $x, $y);
        }

        return $image;
    }
}
