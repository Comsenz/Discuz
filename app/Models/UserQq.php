<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $userid
 * @property string $openid
 * @property string $nickname
 * @property int $sex
 * @property string $province
 * @property string $city
 * @property string $country
 * @property string $headimgurl
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @method static create($user)
 * @method static where(...$params)
 */
class UserQq extends Model
{
    protected $table = 'user_qq';

    protected $fillable = ['user_id', 'openid', 'nickname','sex', 'city', 'province', 'headimgurl'];

    public static function build(array $data)
    {
        $userQQ = new static;
        $userQQ->attributes = $data;
        return $userQQ;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
