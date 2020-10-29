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

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $token
 * @property string $scope
 * @property int $user_id
 * @property string $payload
 * @property Carbon $created_at
 * @property Carbon $expired_at
 * @property User|null $user
 */
class SessionToken extends Model
{
    const WECHAT_PC_LOGIN = 'wechat_pc_login'; // 微信 PC 扫码登陆

    const WECHAT_PC_BIND = 'wechat_pc_bind'; // 微信 PC 绑定

    /**
     * {@inheritdoc}
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['user_id', 'payload'];

    /**
     * {@inheritdoc}
     */
    protected $casts = ['payload' => 'array'];

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at', 'expired_at'];

    /**
     * {@inheritdoc}
     */
    protected $primaryKey = 'token';

    /**
     * @param string $scope 作用域，相当于名字，但同时可以存在多个同名的
     * @param array|null $payload 负载，要存的数据，可以为 null
     * @param int|null $userId 用户 id，可以为 null
     * @param int $lifetime 生存时间，单位秒，默认 5 分钟
     * @return static
     */
    public static function generate(string $scope, array $payload = null, int $userId = null, $lifetime = 300)
    {
        $token = new static;

        $now = Carbon::now();

        $token->token = Str::random(40);
        $token->scope = $scope;
        $token->payload = $payload;
        $token->user_id = $userId;
        $token->created_at = $now;
        $token->expired_at = $now->addSeconds($lifetime);

        return $token;
    }

    /**
     * @param string $token
     * @param string|null $scope
     * @param int|null $userId
     * @return bool
     */
    public static function check(string $token, string $scope = null, int $userId = null)
    {
        return self::query()
            ->where('token', $token)
            ->when($scope, function (Builder $query, $scope) {
                $query->where('scope', $scope);
            })
            ->where('user_id', $userId)
            ->where('expired_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * @param string $token
     * @param string|null $scope
     * @param int|null $userId
     * @return SessionToken
     */
    public static function get(string $token, string $scope = null, int $userId = null)
    {
        return self::query()
            ->where('token', $token)
            ->when($scope, function (Builder $query, $scope) {
                $query->where('scope', $scope);
            })
            ->when($userId, function (Builder $query, $userId) {
                $query->where('user_id', $userId);
            })
            ->where('expired_at', '>', Carbon::now())
            ->first();
    }

    /**
     * Define the relationship with the owner of this password token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
