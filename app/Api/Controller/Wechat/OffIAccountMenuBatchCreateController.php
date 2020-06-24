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
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use EasyWeChat\Factory;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountMenuBatchCreateController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * @var Factory
     */
    protected $easyWechat;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * WechatMiniProgramCodeController constructor.
     *
     * @param Factory $easyWechat
     * @param Validator $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $easyWechat, Validator $validator, SettingsRepository $settings)
    {
        $this->validator = $validator;
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
     * @throws GuzzleException
     * @throws InvalidConfigExceptionAlias
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
