<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\WechatJssdkSerializer;
use App\Exceptions\TranslatorException;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Tobscure\JsonApi\Document;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountJSSDKController extends AbstractCreateController
{
    use EasyWechatTrait;

    public $serializer = WechatJssdkSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param Dispatcher $bus
     * @param UrlGenerator $url
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url)
    {
        $this->bus = $bus;
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

        $app = $this->offiaccount();

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
