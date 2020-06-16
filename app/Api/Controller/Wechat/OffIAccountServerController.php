<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Message;

/**
 * @package App\Api\Controller\Wechat
 */
class OffIAccountServerController implements RequestHandlerInterface
{
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
            'token' => $this->settings->get('offiaccount_server_config_token', 'wx_offiaccount'),
            'response_type' => 'array',

            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => storage_path('logs/easywechat.log'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => storage_path('logs/easywechat.log'),
                        'level' => 'info',
                    ],
                ],
            ],
        ];

        $this->easyWechat = $easyWechat::officialAccount($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // app('wechatOffiaccount')->info('notify: ', (array) $request->getQueryParams());

        $this->easyWechat->server->push(TextMessageHandler::class, Message::TEXT);  // 粉丝文本
        // $this->easyWechat->server->push(ReplyMessageHandler::class, Message::TEXT); // 粉丝回复
        $this->easyWechat->server->push(EventMessageHandler::class, Message::EVENT); // 粉丝事件
        $this->easyWechat->server->push(ImageMessageHandler::class, Message::IMAGE); // 粉丝图片
        $this->easyWechat->server->push(VoiceMessageHandler::class, Message::VOICE); // 粉丝语音
        $this->easyWechat->server->push(VideoMessageHandler::class, Message::VIDEO); // 粉丝视频
        $this->easyWechat->server->push(LocationMessageHandler::class, Message::LOCATION); // 粉丝坐标
        $this->easyWechat->server->push(LinkMessageHandler::class, Message::LINK); // 粉丝链接
        $this->easyWechat->server->push(FileMessageHandler::class, Message::FILE); // 粉丝文件

        $response = $this->easyWechat->server->serve();

        $response->send();
    }
}
