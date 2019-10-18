<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifySerializer.php 28830 2019-10-14 11:33 chenkeke $
 */

namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class ClassifySerializer extends AbstractSerializer
{
    protected $type = 'classify';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'icon' => $model->icon,
            'description' => $model->description,
            'property' => $model->property,
            'sort' => $model->sort,
            'threads' => $model->threads?:0
        ];
    }
}