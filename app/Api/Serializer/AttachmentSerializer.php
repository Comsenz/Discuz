<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Commands\Attachment\CreateAttachment;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
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
     * @param UrlGenerator $url
     * @param Filesystem $filesystem
     */
    public function __construct(UrlGenerator $url, Filesystem $filesystem)
    {
        $this->url = $url;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        $path = $model->file_path.'/'.$model->attachment;

        // 当使用本地存储时，曾经上传的远程附件也只能尝试从本地获取
        if ($this->filesystem->getDriver()->getAdapter() instanceof Local) {
            $model->is_remote = false;
        }

        $uri = $this->filesystem->url($path);

        $url = $model->is_remote
            ? $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath()
            : $this->url->to('/storage/attachment/' . $model->attachment);

        $fixWidth = CreateAttachment::FIX_WIDTH;

        $attributes = [
            'isGallery'         => $model->is_gallery,
            'isRemote'          => $model->is_remote,
            'url'               => $url,
            'attachment'        => $model->attachment,
            'extension'         => Str::afterLast($model->attachment, '.'),
            'fileName'          => $model->file_name,
            'filePath'          => $model->file_path,
            'fileSize'          => (int) $model->file_size,
            'fileType'          => $model->file_type,
        ];

        if ($model->is_gallery) {
            $attributes['thumbUrl'] = $model->is_remote
                ? $this->filesystem->getDriver()->getAdapter()->getPicUrl($path).'?imageMogr2/thumbnail/'.$fixWidth.'x/interlace/0'
                : Str::replaceLast('.', '_thumb.', $url);
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
