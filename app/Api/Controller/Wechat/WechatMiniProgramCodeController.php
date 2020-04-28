<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use EasyWeChat\Factory;

/**
 * 微信小程序 - 小程序码
 *
 * @package App\Api\Controller\Wechat
 */
class WechatMiniProgramCodeController implements RequestHandlerInterface
{
    /**
     * @var Factory
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * WechatMiniProgramCodeController constructor.
     *
     * @param Factory $easyWechat
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $easyWechat, SettingsRepository $settings)
    {
        $this->easyWechat = $easyWechat;
        $this->settings = $settings;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = Arr::get($request->getParsedBody(), 'data', []);

        $path = Arr::get($data, 'attributes.path', 'path/to/page');
        $width = Arr::get($data, 'attributes.width', '');
        $colorR = Arr::get($data, 'attributes.color.r', '');
        $colorG = Arr::get($data, 'color.g', '');
        $colorB = Arr::get($data, 'color.b', '');

        $config = [
            'app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
            'secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram'),
        ];

        $app = $this->easyWechat::miniProgram($config);

        return $app->app_code->get($path, [
            'width' => $width,
            'line_color' => [
                'r' => $colorR,
                'g' => $colorG,
                'b' => $colorB,
            ],
        ]);
    }
}
