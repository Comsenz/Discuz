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
use Illuminate\Validation\Factory as Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountMenuBatchCreateController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * WechatMiniProgramCodeController constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;

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

        $data = Arr::get($request->getParsedBody(), 'data');

        $build = [];
        collect($data)->each(function ($item) use (&$build) {
            $attribute = Arr::get($item, 'attributes');
            array_push($build, $attribute);
        });

        $result = $this->easyWechat->menu->create($build);

        if (array_key_exists('errmsg', $result) && $result['errmsg'] != 'ok') {
            throw new \Exception($result['errmsg']);
        }

        return DiscuzResponseFactory::JsonApiResponse($result);
    }
}
