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
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Support\Collection;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountAssetUpdateController extends AbstractCreateController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var string
     */
    public $serializer = OffIAccountAssetSerializer::class;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var OffIAccountAssetUpdateValidator
     */
    protected $validator;

    /**
     * @param OffIAccountAssetUpdateValidator $validator
     */
    public function __construct(OffIAccountAssetUpdateValidator $validator)
    {
        $this->validator = $validator;
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|Collection|mixed|object|ResponseInterface|string
     * @throws PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $mediaId = Arr::get($request->getParsedBody(), 'data.media_id', '');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', '');

        $this->validator->valid($attributes);

        // TODO 指定更新多图文中的第 2 篇
        // $result = $this->easyWechat->material->updateArticle($mediaId, new Article(...), 1); // 第 2 篇

        return $this->easyWechat->material->updateArticle($mediaId, $attributes);
    }

}
