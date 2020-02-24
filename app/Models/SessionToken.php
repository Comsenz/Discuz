<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $token
 * @property string $scope
 * @property int $user_id
 * @property string $payload
 * @property Carbon $created_at
 * @property Carbon $expired_at
 * @package App\Models
 */
class SessionToken extends Model
{
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
     * @param string $scope
     * @param int|null $userId
     * @return bool
     */
    public static function check(string $token, string $scope, int $userId = null)
    {
        return self::where('token', $token)
            ->where('scope', $scope)
            ->where('user_id', $userId)
            ->where('expired_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * @param string $token
     * @param string $scope
     * @param int|null $userId
     * @return SessionToken
     */
    public static function get(string $token, string $scope, int $userId = null)
    {
        return self::where('token', $token)
            ->where('scope', $scope)
            ->where('user_id', $userId)
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
