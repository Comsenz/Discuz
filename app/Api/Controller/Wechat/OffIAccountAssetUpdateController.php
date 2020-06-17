<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountAssetSerializer;
use App\Validators\OffIAccountAssetUpdateValidator;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use EasyWeChat\Factory;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetUpdateController extends AbstractCreateController
{
    use AssertPermissionTrait;

    /**
     * @var string
     */
    public $serializer = OffIAccountAssetSerializer::class;

    /**
     * @var Factory
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var OffIAccountAssetUpdateValidator
     */
    protected $validator;

    /**
     * @param Factory $easyWechat
     * @param SettingsRepository $settings
     * @param OffIAccountAssetUpdateValidator $validator
     */
    public function __construct(Factory $easyWechat, SettingsRepository $settings, OffIAccountAssetUpdateValidator $validator)
    {
        $this->settings = $settings;
        $this->validator = $validator;

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
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getParsedBody(), 'data.id', '');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', '');

        $this->validator->valid($attributes);

        // TODO 指定更新多图文中的第 2 篇
        // $result = $this->easyWechat->material->updateArticle($mediaId, new Article(...), 1); // 第 2 篇

        return $this->easyWechat->material->updateArticle($mediaId, $attributes);
    }

}
