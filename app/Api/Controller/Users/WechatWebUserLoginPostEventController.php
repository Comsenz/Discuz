<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Commands\Users\WebUserEvent;
use App\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EasyWeChat\Factory;
use Psr\Http\Server\RequestHandlerInterface;
use Discuz\Http\DiscuzResponseFactory;


class WechatWebUserLoginPostEventController implements RequestHandlerInterface
{
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
            'app_id'=> $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret'=>$this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'token' => $this->settings->get('oplatform_app_token', 'wx_oplatform'),
            'aes_key' => $this->settings->get('oplatform_app_aes_key', 'wx_oplatform')
        ];
        $app = Factory::officialAccount($wx_config);
        return $this->bus->dispatch(
            new WebUserEvent($app)
        );
    }
}
