<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Models\Setting;
use App\Settings\SettingsRepository;
use EasyWeChat\Factory;

class WechatWebUserLoginController
{
    /**
     * 配置信息
     * @var SettingsRepository
     */
    protected $settings;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
    public function handle()
    {
        $app = Factory::officialAccount(
            ['app_id'=>$this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret'=>$this->settings->get('offiaccount_app_secret', 'wx_offiaccount')
            ]);
        $token = $app->access_token->getToken();
        $result = $app->qrcode->temporary('foo', 6 * 24 * 3600);
        $url = $app->qrcode->url($result['ticket']);

        dd($token);
        dd($result);
        dd($url);
    }
}
