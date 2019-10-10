<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWordSerializer.php xxx 2019-09-26 16:22:00 LiuDongdong $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class StopWordSerializer extends AbstractSerializer
{
    protected $type = 'StopWord';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'type' => $model->type,
            'class' => $model->class,
            'find' => $model->find,
            'replacement' => $model->replacement,
            'create_at' => $model->create_at,
            'update_at' => $model->update_at,
        ];
    }
}
