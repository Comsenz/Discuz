<?php


namespace App\WechatMessageHandler;

use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class EventMessageHandler extends MessageEventHandlerInterface
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $event;

    /**
     * @var mixed
     */
    protected $fromUserOpenid;

    /**
     * @var mixed
     */
    protected $eventKey;

    public function __construct($app)
    {
        $message = $app->server->getMessage();
        $this->event = $message['Event'];
        $this->fromUserOpenid = $message['FromUserName'];
        $this->eventKey = $message['EventKey'];
    }

    public function handle($payload = null)
    {
        if ($this->isNewSubscribe()) {
            return '你好，欢迎关注';
        }

        return '事件消息';
    }

    /**
     * 是否订阅关注
     *
     * @return bool
     */
    private function isNewSubscribe()
    {
        if ($this->event == 'subscribe' && $this->eventKey == null) {
            return true;
        }

        return false;
    }

    /**
     * TODO 是否是扫码关注
     *
     * @return bool
     */
    private function isNewSubscribeFromQrCode()
    {
        if ($this->event == 'subscribe' && $this->eventKey !== null) {
            return true;
        }

        return false;
    }

}
