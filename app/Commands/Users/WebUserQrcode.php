<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;
use App\Exceptions\QrcodeImgException;
use App\Models\SessionToken;
use Discuz\Http\DiscuzResponseFactory;
use EasyWeChat\Factory;

class WebUserQrcode
{
    /**
     * 微信参数
     *
     * @var string
     */
    protected $wx_config;

    protected $sessionId;

    public function __construct(array $wx_config,string $sessionId)
    {
        $this->wx_config = $wx_config;
        $this->sessionId = $sessionId;
    }


    public function handle()
    {
        $app = Factory::officialAccount($this->wx_config);
        $token = SessionToken::generate('', null);
        $result = $app->qrcode->temporary($token->token,  60*5);
        $url = $app->qrcode->url($result['ticket']);
        if (!$token->save()){
            throw new QrcodeImgException(trans('login.WebUser_img_error'));
        }
        $data = [
            'code' => 200,
            'scene_str' => $token->token,
            'img' => $url,
        ];
        return DiscuzResponseFactory::JsonResponse($data);

    }

}
