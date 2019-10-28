<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{

    protected $type = 'user';

    public function getDefaultAttributes($model)
    {
        return  [
            'id'         => $model->id,
            'username'   => $model->username,
            'adminid'    => $model->adminid,
            'unionid'    => $model->unionid,
            'mobile'     => $model->mobile,
            'createtime' => $model->createtime,
            'nickname'   => $model->userWechats?$model->userWechats->nickname:$model->userWechats,
        ];

    }
}
