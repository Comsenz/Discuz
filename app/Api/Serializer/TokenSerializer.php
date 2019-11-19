<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class TokenSerializer extends AbstractSerializer
{

    protected $type = 'token';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        if(isset($model['location'])) {
            return [
                'location' => $model['location']
            ];
        } else {
            return [
                'token_type' => $model->token_type,
                'expires_in' => $model->expires_in,
                'access_token' => $model->access_token,
                'refresh_token' => $model->refresh_token,
            ];
        }
    }

    public function getId($model)
    {
        return 1;
    }
}
