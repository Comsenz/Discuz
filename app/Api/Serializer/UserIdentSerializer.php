<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserIdentSerializer extends AbstractSerializer
{

    protected $type = 'user_ident';

    public function getDefaultAttributes($model)
    {
        return  [
            'id'         => $model->id,
            'mobile'     => $model->mobile?substr($model->mobile, 0, 3).'****'.substr($model->mobile, 7):"",
            'code'       => $model->code,
        ];

    }
}
