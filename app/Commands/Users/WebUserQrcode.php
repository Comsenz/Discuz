<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Exceptions\QrcodeImgException;
use App\Models\SessionToken;
use EasyWeChat\Factory;

class WebUserQrcode
{
    /**
     * 微信参数
     *
     * @var string
     */
    protected $wx_config;

    public function __construct(array $wx_config)
    {
        $this->wx_config = $wx_config;
    }


    public function handle()
    {
        $app = Factory::officialAccount($this->wx_config);
        $token = SessionToken::generate('wechat');
        $result = $app->qrcode->temporary($token->token,  60*5);
        $url = $app->qrcode->url($result['ticket']);
        if (!$token->save()){
            throw new QrcodeImgException(trans('login.WebUser_img_error'));
        }

        return [
            'scene_str' => $token->token,
            'img' => $url,
        ];

    }

}
