<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Settings\SettingsRepository;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Discuz\Http\DiscuzResponseFactory;

class WechatWebUserLoginEventController implements RequestHandlerInterface
{
    use EasyWechatTrait;

    /**
     * 微信参数
     *
     * @var string
     */
    protected $settings;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param SettingsRepository $setting
     * @param Dispatcher $bus
     */
    public function __construct(SettingsRepository $setting, Dispatcher $bus)
    {
        $this->settings = $setting;
        $this->bus = $bus;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $wx_config = [
            'token' => $this->settings->get('oplatform_app_token', 'wx_oplatform'),
            'aes_key' => $this->settings->get('oplatform_app_aes_key', 'wx_oplatform')
        ];
        $app = $this->offiaccount($wx_config);
        $response  = $app->server->serve();

        return DiscuzResponseFactory::HtmlResponse($response->getContent());

    }
}
