<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array)
 * @method truncate()
 * @method insert(array $array)
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'tag'];

    protected $primaryKey = ['key', 'tag'];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public static $encrypt;

    /**
     * 需要加密的数据字段
     *
     * @var array
     */
    public static $checkEncrypt = [
        'app_id',
        'app_secret',
        'api_key',
        'offiaccount_app_id',
        'offiaccount_app_secret',
        'miniprogram_app_id',
        'miniprogram_app_secret',
        'oplatform_app_id',
        'oplatform_app_secret',
        'qcloud_secret_id',
        'qcloud_secret_key',
        'qcloud_sms_app_id',
        'qcloud_sms_app_key',
        'qcloud_sms_template_id',
        'qcloud_sms_sign',
        'qcloud_captcha_app_id',
        'qcloud_captcha_secret_key',
        'qcloud_vod_url_key',
        'offiaccount_server_config_token',
    ];

    /**
     * Set the encrypt.
     *
     * @param $encrypt
     */
    public static function setEncrypt($encrypt)
    {
        self::$encrypt = $encrypt;
    }

    /**
     * each data decrypt
     */
    public function existDecrypt()
    {
        if (in_array($this->key, self::$checkEncrypt)) {
            return;
        }
    }

    /**
     * 解密数据
     *
     * @param $value
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getValueAttribute($value)
    {
        if (in_array($this->key, self::$checkEncrypt)) {
            $value = empty($value) ? $value : static::$encrypt->decrypt($value);
        }

        return $value;
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
        if (in_array($key, self::$checkEncrypt)) {
            $value = static::$encrypt->encrypt($value);
        }
    }
}
