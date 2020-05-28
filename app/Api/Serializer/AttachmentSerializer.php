<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Attachment;
use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
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
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     *
     * @param Attachment $model
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

        if ($model->is_remote) {
            $url = $this->filesystem->disk('attachment_cos')->temporaryUrl($path, Carbon::now()->addMinutes(5));
        } else {
            $url = $this->filesystem->disk('attachment')->url($path);
        }

        $fixWidth = Attachment::FIX_WIDTH;

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
                    ? $url . '&imageMogr2/thumbnail/' . $fixWidth . 'x' . $fixWidth
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
