<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\QrSerializer;
use App\Commands\Users\WebUserQrcode;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatWebUserLoginController extends AbstractResourceController
{
    /**
     * 微信参数
     *
     * @var string
     */
    protected $settings;
    protected $bus;
    public $serializer = QrSerializer::class;

    public function __construct(SettingsRepository $setting, Dispatcher $bus)
    {
        $this->settings = $setting;
        $this->bus = $bus;
    }


    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $wx_config = [
            'app_id'=> $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret'=>$this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
        ];

        return $this->bus->dispatch(
            new WebUserQrcode($wx_config)
        );
    }
}
