<?php


namespace App\WechatMessageHandler;

use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class ReplyMessageHandler extends MessageEventHandlerInterface
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
        $message = $app->server->getMessage();
        $this->content = $message['Content'];
    }

    public function handle($payload = null)
    {
        return '消息回复 !';
    }
}
