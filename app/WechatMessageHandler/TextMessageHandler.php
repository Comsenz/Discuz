<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\WechatMessageHandler;

use App\Models\WechatOffiaccountReply;
use Discuz\Wechat\EasyWechatTrait;
use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class TextMessageHandler extends MessageEventHandlerInterface
{
    use EasyWechatTrait;

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

        $this->easyWechat = $this->offiaccount();
    }

    public function handle($payload = null)
    {
        try {
            // 匹配查询
            $bool = WechatOffiaccountReply::match($this->content, $this->reply);
            if (!$bool) {
                return $this->error(''); // 匹配不到不回复
            }

            $type = WechatOffiaccountReply::enumReplyType($this->reply->reply_type);
            if (empty($type)) {
                return $this->{'Text'}($this->error());
            }

            /**
             * 回复对应类型
             * (目前微信不支持 链接消息、坐标消息)
             *
             * @var object $result
             */
            $result = $this->{$type}();

            return $result;

        } catch (\Exception $e) {
            $this->wechatDebugLog($e->getMessage());

            return $this->{'Text'}($this->error());
        }
    }

    /**
     * @param string $argSting
     * @return string
     */
    public function error($argSting = '系统加载，请稍后再试...') : string
    {
        return $argSting;
    }
}
