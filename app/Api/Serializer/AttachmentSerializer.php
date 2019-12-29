<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Http\UrlGenerator;
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

    /**
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'isGallery'         => $model->is_gallery,
            'isRemote'          => $model->is_remote,
            'url'               => $this->url->to('/storage/attachment/' . $model->attachment),
            'attachment'        => $model->attachment,
            'extension'         => Str::afterLast($model->attachment, '.'),
            'fileName'          => $model->file_name,
            'filePath'          => $model->file_path,
            'fileSize'          => (int) $model->file_size,
            'fileType'          => $model->file_type,
        ];
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
