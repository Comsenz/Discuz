<?php


namespace App\WechatMessageHandler;

use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class VideoMessageHandler extends MessageEventHandlerInterface
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
        //
    }

    public function handle($payload = null)
    {
        // TODO: Implement handle() method.
    }
}
