<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Commands\Attachment\CreateAttachment;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Support\Str;
use Tobscure\JsonApi\Relationship;

class AttachmentSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'attachments';

    /**
     * @var UrlGenerator
     */
    protected $url;

    /*
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * AttachmentSerializer constructor.
     * @param UrlGenerator $url
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     */
    public function __construct(UrlGenerator $url, Filesystem $filesystem, SettingsRepository $settings)
    {
        $this->url = $url;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        // 是否返回模糊图
        $blur = (bool) $model->getAttribute('blur');

        if ($blur) {
            $parts = explode('.', $model->attachment);
            $parts[0] = md5($parts[0]);

            $model->attachment = implode('_blur.', $parts);
        }

        $path = $model->file_path . '/' . $model->attachment;

        $url = $this->filesystem->disk($model->is_remote ? 'attachment_cos' : 'attachment')->url($path);

        $fixWidth = CreateAttachment::FIX_WIDTH;

        $attributes = [
            'order'             => $model->order,
            'isGallery'         => $model->is_gallery,
            'isRemote'          => $model->is_remote,
            'isApproved'        => $model->is_approved,
            'url'               => $url,
            'attachment'        => $model->attachment,
            'extension'         => Str::afterLast($model->attachment, '.'),
            'fileName'          => $model->file_name,
            'filePath'          => $model->file_path,
            'fileSize'          => (int) $model->file_size,
            'fileType'          => $model->file_type,
        ];

        // 图片缩略图地址
        if ($model->is_gallery) {
            if ($blur) {
                $attributes['thumbUrl'] = $url;
            } else {
                $attributes['thumbUrl'] = $model->is_remote
                    ? $url . '?imageMogr2/thumbnail/' . $fixWidth . 'x' . $fixWidth
                    : Str::replaceLast('.', '_thumb.', $url);
            }
        }

        return $attributes;
    }

    /**
     * @param $attachment
     * @return Relationship
     */
    protected function user($attachment)
    {
        return $this->hasOne($attachment, UserSerializer::class);
    }

    /**
     * @param $attachment
     * @return Relationship
     */
    public function post($attachment)
    {
        return $this->hasOne($attachment, PostSerializer::class);
    }
}
