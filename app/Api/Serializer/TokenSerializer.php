<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
        $build = [
            'token_type' => $model->token_type,
            'expires_in' => $model->expires_in,
            'access_token' => $model->access_token,
            'refresh_token' => $model->refresh_token,
        ];

        if (array_key_exists('pc_login', $model)) {
            $build += ['pc_login' => $model->pc_login];
        }

        return $build;
    }

    public function getId($model)
    {
        if (array_key_exists('login_get_id', $model)) {
            if ($model) {
                return 1;
            }
        }

        return static::$user->id;
    }

    public function existsPcLogin($model, $getId = null)
    {
        if (array_key_exists('pc_login', $model)) {
            if (is_null($getId)) {
                return true;
            } elseif ($getId === true) {

            } else {

            }

            return true;
        }

        return false;
    }

    public function users($model)
    {
        return $this->hasOne(['users' => static::$user], UserProfileSerializer::class);
    }
}
