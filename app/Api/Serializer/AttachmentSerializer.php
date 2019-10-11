<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: AttachmentSerializer.php 28830 2019-09-29 16:56 chenkeke $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class AttachmentSerializer extends AbstractSerializer
{
    protected $type = 'attachment';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'post_id' => $model->post_id,
            'attachment' => $model->attachment,
            'file_name' => $model->file_name,
            'file_size' => $model->file_size,
            'file_type' => $model->file_type,
            'remote' => $model->remote
        ];
    }
}