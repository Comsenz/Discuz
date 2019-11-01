<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;


class UserPorfileSerializer extends AbstractSerializer
{

    protected $type = 'user';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'username' => $model->username,
            'adminid' => $model->adminid,
            'unionid' => $model->unionid,
            'mobile'     => $model->mobile?substr($model->mobile, 0, 3).'****'.substr($model->mobile, 7):"",
            'createtime' => $model->createtime,
            'login_ip' => $model->login_ip,
            'nickname' => $model->nickname,
            'sex' => $model->sex,
            'icon' => $model->icon
        ];
    }
}
