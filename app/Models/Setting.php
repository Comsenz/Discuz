<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\Encrypter;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array)
 * @method truncate()
 * @method static insert()
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'tag'];
    protected $primaryKey = ['key', 'tag'];
    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * 需要加密的数据字段
     *
     * @var array
     */
    public static $encrypt = [
        'offiaccount_app_id',
        'offiaccount_app_secret',
        'miniprogram_app_id',
        'miniprogram_app_secret',
        'oplatform_app_id',
        'oplatform_app_secret',
        'qcloud_app_id',
        'qcloud_secret_id',
        'qcloud_secret_key',
        'qcloud_sms_app_id',
        'qcloud_sms_app_key',
    ];

    /**
     * 解密数据
     *
     * @param $key
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getKeyAttribute($key)
    {
        if (in_array($key, self::$encrypt)) {
            $this->value = app()->make('encrypter')->decrypt($this->value);
        }

        return $key;
    }

    /**
     * 加密数据
     * (insert 和 update 不操作 Eloquent)
     *
     * @param $key
     * @param $value
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function setValue($key, &$value)
    {
        if (in_array($key, self::$encrypt)) {
            $value = app()->make('encrypter')->encrypt($value);
        }
    }

}
