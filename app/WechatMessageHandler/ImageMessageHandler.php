<?php


namespace App\WechatMessageHandler;

use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class ImageMessageHandler extends MessageEventHandlerInterface
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $content;

    public function __construct($app)
    {
        app('wechatOffiaccount')->info(self::class, (array) $app->server->getMessage());
        dd('打印');

        $message = $app->server->getMessage();
        $this->content = $message['Content'];
    }

    public function handle($payload = null)
    {
        // 检测有无关键词(全匹配/模糊匹配)

        // 是否设定消息回复

        return '文本消息(关键词消息) !';
    }
}
