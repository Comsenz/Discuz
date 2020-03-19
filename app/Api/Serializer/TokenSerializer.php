<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class TokenSerializer extends AbstractSerializer
{
    protected $type = 'token';

    protected static $user;

    public static function setUser($user)
    {
        static::$user = $user;
    }

    public static function getUser()
    {
        return static::$user;
    }

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'token_type' => $model->token_type,
            'expires_in' => $model->expires_in,
            'access_token' => $model->access_token,
            'refresh_token' => $model->refresh_token,
        ];
    }

    public function getId($model)
    {
        return static::$user->id;
    }

    public function users($model)
    {
        return $this->hasOne(['users' => static::$user], UserSerializer::class);
    }
}
