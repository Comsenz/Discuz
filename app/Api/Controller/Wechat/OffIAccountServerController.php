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

use App\WechatMessageHandler\EventMessageHandler;
use App\WechatMessageHandler\FileMessageHandler;
use App\WechatMessageHandler\ImageMessageHandler;
use App\WechatMessageHandler\LinkMessageHandler;
use App\WechatMessageHandler\LocationMessageHandler;
use App\WechatMessageHandler\TextMessageHandler;
use App\WechatMessageHandler\VideoMessageHandler;
use App\WechatMessageHandler\VoiceMessageHandler;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountServerController implements RequestHandlerInterface
{
    use EasyWechatTrait;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;

        $config = [
            'token' => $this->settings->get('offiaccount_server_config_token', 'wx_offiaccount'),
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/easyWechatOffiaccountDev.log'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => storage_path('logs/easyWechatOffiaccount.log'),
                        'level' => 'info',
                    ],
                ],
            ],
        ];

        $this->easyWechat = $this->offiaccount($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // app('wechatOffiaccount')->info(self::class . ': ', (array)$request->getQueryParams());

        /**
         * 判断是否是服务器配置
         */
        $params = Arr::wrap($request->getQueryParams());
        if (Arr::has($params, 'signature')) {
            $this->easyWechat->server->push(TextMessageHandler::class, Message::TEXT);   // 粉丝文本
            $this->easyWechat->server->push(EventMessageHandler::class, Message::EVENT); // 粉丝事件
            // TODO 未使用的可扩展事件，监听粉丝发送给公众号的信息
            $this->easyWechat->server->push(ImageMessageHandler::class, Message::IMAGE); // 粉丝发图片
            $this->easyWechat->server->push(VoiceMessageHandler::class, Message::VOICE); // 粉丝发语音
            $this->easyWechat->server->push(VideoMessageHandler::class, Message::VIDEO); // 粉丝发视频
            $this->easyWechat->server->push(LocationMessageHandler::class, Message::LOCATION); // 粉丝发坐标
            $this->easyWechat->server->push(LinkMessageHandler::class, Message::LINK); // 粉丝发链接
            $this->easyWechat->server->push(FileMessageHandler::class, Message::FILE); // 粉丝发文件
        }

        $response = $this->easyWechat->server->serve();

        $response->send();
    }
}
