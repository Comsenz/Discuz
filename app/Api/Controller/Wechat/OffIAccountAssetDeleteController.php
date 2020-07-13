<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信公众号 - 删除单条永久素材
 *
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetDeleteController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

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
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getQueryParams(), 'media_id');

        // 获取永久素材
        $response = $this->easyWechat->material->delete($mediaId);

        return DiscuzResponseFactory::JsonApiResponse($response);
    }
}
