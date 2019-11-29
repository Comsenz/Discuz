<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: AttachmentSerializer.php 28830 2019-09-29 16:56 chenkeke $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class AttachmentSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'attachments';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'isGallery'         => (int) $model->is_gallery,
            'isRemote'          => (int) $model->is_remote,
            'attachment'        => $model->attachment,
            'fileName'          => $model->file_name,
            'filePath'          => $model->file_path,
            'fileSize'          => $model->file_size,
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
