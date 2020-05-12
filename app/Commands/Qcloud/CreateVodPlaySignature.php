<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Qcloud;

use App\Models\User;
use App\Settings\SettingsRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Support\Str;

class CreateVodPlaySignature
{
    use AssertPermissionTrait;

    /**
     * 签名过期时间
     */
    const EXPIRETIME = 86400;

    protected $actor;

    protected $data;

    protected $settings;

    public function __construct(User $actor, $data)
    {
        $this->actor            = $actor;
        $this->data             = $data;
    }

    public function handle(SettingsRepository $settings)
    {
        $this->settings = $settings;

        return call_user_func([$this, '__invoke']);
    }

    public function __invoke()
    {
        $this->assertRegistered($this->actor);

        $appId = $this->settings->get('qcloud_app_id', 'qcloud');
        $secretId = $this->settings->get('qcloud_secret_id', 'qcloud');
        $secretKey = $this->settings->get('qcloud_secret_key', 'qcloud');
        $subAppId = $this->settings->get('qcloud_vod_sub_app_id', 'qcloud') ?: 0;
        $urlKey = $this->settings->get('qcloud_vod_url_key', 'qcloud');
        if (!$secretId || !$secretKey) {
            throw new PermissionDeniedException;
        }

        $currentTime = Carbon::now()->timestamp;

        $header = ['alg'=>'HS256','typ'=>'JWT'];

        $payLoad = [
            'appId'                  => $appId,
            'fileId'                 => 1,
            'currentTimeStamp'       => $currentTime,
            'expireTimeStamp'        => $currentTime + self::EXPIRETIME,    //签名到期时间戳
            'pcfg'                   => 'basicDrmPreset',                   //超级播放配置名称
//            'urlAccessInfo'          => [                                   //播放链接的防盗链配置参数
//                't'              => $currentTime + self::EXPIRETIME,
//                'rlimit'         => 3,
//                'us'             => Str::random(10),
//            ],
//            'drmLicenseInfo'         => [                                   //加密内容的密钥配置参数
//                'expireTimeStamp'=>$currentTime + self::EXPIRETIME
//            ],
        ];
        $header = http_build_query($header);
        $payLoad = http_build_query($payLoad);
        $signature = hash_hmac('HS256', base64_encode($header) . '.' . base64_encode($payLoad), $urlKey, true);
        $token = base64_encode($header) . '.' . base64_encode($payLoad) . '.' . base64_encode($signature);

        return $token;
    }
}
