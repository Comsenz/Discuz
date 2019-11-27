<?php


namespace App\Api\Serializer;


class UserProfileSerializer extends UserSerializer
{

    public function getDefaultAttributes($model)
    {
        $attributes = parent::getDefaultAttributes($model);

        return $attributes + [
            'payd' => $model->payd,
            'payTime' => $this->formatDate($model->payTime)
            ];
    }
}
