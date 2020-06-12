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
use EasyWeChat\Kernel\Exceptions\InvalidConfigException as InvalidConfigExceptionAlias;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use EasyWeChat\Factory;

/**
 * 获取关键词回复
 *
 * @package App\Api\Controller\Wechat
 */
class OffIAccountListReplyController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * @var Factory
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Factory $easyWechat
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $easyWechat, SettingsRepository $settings)
    {
        $this->settings = $settings;

        $config = [
            'app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'response_type' => 'array',
        ];

        $this->easyWechat = $easyWechat::officialAccount($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws InvalidConfigExceptionAlias
     * @throws PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $response = $this->easyWechat->auto_reply->current();

        return DiscuzResponseFactory::JsonApiResponse($response);
    }
}
