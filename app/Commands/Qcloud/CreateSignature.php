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

class CreateSignature
{
    use AssertPermissionTrait;

    /**
     * 签名过期时间
     */
    const EXPIRETIME = 3600;

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

        $secretId = $this->settings->get('qcloud_secret_id', 'qcloud');
        $secret_key = $this->settings->get('qcloud_secret_key', 'qcloud');
        if (!$secretId || !$secret_key) {
            throw new PermissionDeniedException;
        }

        $currentTime = Carbon::now()->timestamp;

        $original = [
            'secretId'         => $secretId,
            'currentTimeStamp' => $currentTime,
            'expireTime'       => $currentTime + self::EXPIRETIME,
            'random'           => rand(),
        ];

        $original = http_build_query($original);
        return [base64_encode(hash_hmac('SHA1', $original, $secret_key, true).$original)];
    }
}
