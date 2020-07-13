<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信小程序 - 小程序码
 *
 * @package App\Api\Controller\Wechat
 */
class WechatMiniProgramCodeController implements RequestHandlerInterface
{
    use EasyWechatTrait;

    /**
     * WechatMiniProgramCodeController constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getQueryParams();

        $path = Arr::get($data, 'path', '');
        $width = Arr::get($data, 'width', '');
        $colorR = Arr::get($data, 'r', '');
        $colorG = Arr::get($data, 'g', '');
        $colorB = Arr::get($data, 'b', '');

        $app = $this->miniProgram();

        $response = $app->app_code->get($path, [
            'width' => $width,
            'line_color' => [
                'r' => $colorR,
                'g' => $colorG,
                'b' => $colorB,
            ],
        ]);

        $response = $response->withoutHeader('Content-disposition');

        return $response;
    }
}
