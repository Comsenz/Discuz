<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\SessionSerializer;
use App\Commands\Users\WebUserQrcode;
use App\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WechatWebUserLoginController implements RequestHandlerInterface
{
    /**
     * 微信参数
     *
     * @var string
     */
    protected $settings;
    protected $bus;
    public $serializer = SessionSerializer::class;

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
        ];

        $sessionId = Arr::get($request->getQueryParams(), 'sessionId', Str::random());
        return $this->bus->dispatch(
            new WebUserQrcode($wx_config,$sessionId)
        );
    }
}
