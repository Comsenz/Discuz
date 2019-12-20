<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserIdentSerializer extends AbstractSerializer
{
    protected $type = 'user_ident';

    public function getDefaultAttributes($model)
    {
        return  [
            'id'         => $model->id,
            'mobile'     => $model->mobile?substr($model->mobile, 0, 3).'****'.substr($model->mobile, 7):'',
            'code'       => $model->code,
        ];
    }
}
