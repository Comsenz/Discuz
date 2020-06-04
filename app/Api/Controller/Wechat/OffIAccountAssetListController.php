<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\WechatAssetSerializer;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use EasyWeChat\Factory;
use Tobscure\JsonApi\Exception\InvalidParameterException;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetListController extends AbstractCreateController
{
    use AssertPermissionTrait;

    /**
     * @var string
     */
    public $serializer = WechatAssetSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var Factory
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param Dispatcher $bus
     * @param Factory $easyWechat
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     */
    public function __construct(Dispatcher $bus, Factory $easyWechat, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->bus = $bus;
        $this->settings = $settings;
        $this->url = $url;

        $config = [
            'app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'response_type' => 'array',
        ];

        $this->easyWechat = $easyWechat::officialAccount($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|Collection|mixed|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws GuzzleException
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        // 素材的类型，图片（image）、视频（video）、语音（voice）、图文（news）
        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);     // 返回素材的数量
        $offset = $this->extractOffset($request);

        $type = Arr::get($filter, 'type');

        return $this->easyWechat->material->list($type, $offset, $limit);
    }

}
