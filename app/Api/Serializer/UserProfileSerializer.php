<?php

namespace App\Api\Serializer;

class UserProfileSerializer extends UserSerializer
{
    public function getDefaultAttributes($model)
    {
        $attributes = parent::getDefaultAttributes($model);

        return $attributes + [
            'paid' => $model->paid,
            'payTime' => $this->formatDate($model->payTime)
        ];
    }
}
