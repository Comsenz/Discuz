<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleSerializer.php 28830 2019-09-26 09:50 chenkeke $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class CircleSerializer extends AbstractSerializer
{
    protected $type = 'circle';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'icon' => $model->icon,
            'description' => $model->description,
            'property' => $model->property,
            'threads' => $model->threads,
            'membernum' => $model->membernum,
            'tag_on' => $model->tag_on
        ];
    }
}