<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信公众号 - 获取单条永久素材
 *
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetResourceController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * WechatMiniProgramCodeController constructor.
     */
    public function __construct()
    {
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PermissionDeniedException
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getQueryParams(), 'media_id');
        $type = Arr::get($request->getQueryParams(), 'filter.type');

        // 获取永久素材
        $response = $this->easyWechat->material->get($mediaId);

        /**
         * 根据类型数据不同 返回数据形式&格式不同
         */
        switch ($type) {
            case 'image': // 图片（image）
                header('Content-type: image/jpeg');
                return $response;
            case 'video': // 视频（video）
                if (is_array($response)) {
                    return DiscuzResponseFactory::JsonApiResponse($response);
                }
                break;
            case 'voice': // 语音（voice）
                header('Content-type: audio/mpeg');
                return $response;
            case 'news':  // 图文（news）
                if (is_array($response)) {
                    return DiscuzResponseFactory::JsonResponse($response);
                }
                break;
        }

        throw new \Exception('Unexpected value');
    }
}
