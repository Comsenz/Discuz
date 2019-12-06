<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class InfoSerializer extends AbstractSerializer
{

    public function getDefaultAttributes($model)
    {
        return [
            'succeed' => (bool)$model->succeed,
            'error' => $model->error
            ];

    }
}
