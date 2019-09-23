<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{

    protected $type = 'user';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'username' => $model->username,
            'email' => $model->email
        ];
    }
}
