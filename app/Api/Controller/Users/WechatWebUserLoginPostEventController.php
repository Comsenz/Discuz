<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Commands\Users\WebUserEvent;
use App\Settings\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WechatWebUserLoginPostEventController implements RequestHandlerInterface
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
        $this->bus->dispatch(
            new WebUserEvent($app)
        );
        $response  = $app->server->serve();

        return  DiscuzResponseFactory::XmlResponse($response->getContent());
    }
}
