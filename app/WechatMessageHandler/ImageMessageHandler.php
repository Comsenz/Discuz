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
        $message = $app->server->getMessage();
    }

    public function handle($payload = null)
    {
        // TODO: Implement handle() method.
    }
}
