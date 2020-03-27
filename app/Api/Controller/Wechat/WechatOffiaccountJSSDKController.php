<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\WechatJssdkSerializer;
use App\Exceptions\TranslatorException;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Contracts\Setting\SettingsRepository;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Tobscure\JsonApi\Document;
use EasyWeChat\Factory;

/**
 * 微信公众号 - JSSDK
 *
 * Class WechatOffiaccountJSSDKController
 * @package App\Api\Controller\Wechat
 */
class WechatOffiaccountJSSDKController extends AbstractCreateController
{
    public $serializer = WechatJssdkSerializer::class;

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
        $this->easyWechat = $easyWechat;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws TranslatorException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $url = Arr::get($request->getQueryParams(), 'url');
        if (blank($url)) {
            throw new TranslatorException('wechat_invalid_unknown_url_exception');
        }

        $config = [
            'app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'response_type' => 'array',
        ];

        $app = $this->easyWechat::officialAccount($config);

        // js functions
        $build = [
            'updateAppMessageShareData',
            'updateTimelineShareData',
        ];

        $app->jssdk->setUrl($url);

        try {
            $result = $app->jssdk->buildConfig($build, true, false, false);
        } catch (InvalidConfigException $e) {
            throw new TranslatorException('wechat_invalid_config_exception');
        } catch (RuntimeException $e) {
            throw new TranslatorException('wechat_runtime_exception');
        } catch (InvalidArgumentException $e) {
            throw new TranslatorException('wechat_invalid_argument_exception');
        }

        return $result;
    }
}
