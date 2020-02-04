<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Http\UrlGenerator;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Filesystem\Filesystem;
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
        $uri = $this->filesystem->url($model->file_path.'/'.$model->attachment);

        $url = $uri instanceof Uri ? $uri->getScheme().'://'.$uri->getHost().$uri->getPath() : $this->url->to($uri);

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
            $attributes['thumbUrl'] = Str::replaceLast('.', '_thumb.', $url);
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
