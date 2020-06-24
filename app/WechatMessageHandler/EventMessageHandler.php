<?php


namespace App\WechatMessageHandler;

use App\Models\WechatOffiaccountReply;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;
use EasyWeChat\Factory;

class EventMessageHandler extends MessageEventHandlerInterface
{
    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var
     */
    protected $message;

    /**
     * @var mixed
     */
    protected $event;

    /**
     * @var mixed
     */
    protected $eventKey;

    /**
     * @var
     */
    protected $reply;

    public function __construct($app)
    {
        $this->message = $app->server->getMessage();
        app('wechatOffiaccount')->info(self::class . ': ', (array)$this->message);

        $settings = app()->make(SettingsRepository::class);
        $config = [
            'app_id' => $settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret' => $settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'response_type' => 'array',
        ];
        $this->easyWechat = Factory::officialAccount($config);

        $this->event = $this->message['Event'];
        $this->eventKey = $this->message['EventKey'];

        $toUserName = $this->message['ToUserName'];     // 开发者微信号
        $fromUserName = $this->message['FromUserName']; // 发送方帐号（一个OpenID）
        $createTime = $this->message['CreateTime'];     // 消息创建时间（整型）
    }

    public function handle($payload = null)
    {
        if ($this->isNewSubscribe()) {
            // 查询被关注回复
            $attention = WechatOffiaccountReply::where('type', 0)->first();

            if (!empty($attention)) {
                $type = WechatOffiaccountReply::enumReplyType($attention->reply_type);
                if (empty($type)) {
                    return $this->{'Error'}('公众号配置关注信息错误');
                }
                $this->wechatDebugLog($type, $attention->reply_type);
                return $this->{$type}();
            }

            return '';
        }

        /**
         * 触发监听
         * @var mixed $result
         */
        $result = $this->eventByType();

        return $result;
    }

    /**
     * 异步回调事件类型监听
     *
     * @return string
     */
    public function eventByType()
    {
        /**
         * 注意：微信PC端无法触发除Click事件外的事件（微信的Bug）
         */
        switch ($this->event) {
            case 'CLICK': // 点击菜单
                return $this->clickEvent();
            case 'VIEW': // 点击菜单跳转链接时的事件推送 、view_limited图文消息
                $this->viewEvent();
                break;
            case 'view_miniprogram': // 跳转小程序
                $this->viewMiniProgramEvent();
                break;
            case 'location_select': // 发送位置
                $this->locationSelectEvent();
                break;
            case 'TEMPLATESENDJOBFINISH': // 微信推送
                $this->templateEvent();
                break;
            default:
                return $this->{'Error'}('系统配置错误');
        }
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

    private function clickEvent()
    {
        $key = $this->eventKey; // key值

        // 匹配查询
        $bool = WechatOffiaccountReply::match($key, $this->reply);
        if (!$bool) {
            return $this->{'Error'}('公众号配置错误'); // 匹配不到不回复
        }

        $type = WechatOffiaccountReply::enumReplyType($this->reply->reply_type);
        if (empty($type)) {
            return $this->{'Error'}('公众号配置错误');
        }

        return $this->{$type}();
    }

    private function viewEvent()
    {
        $url = $this->eventKey; // 跳转的地址
    }

    private function viewMiniProgramEvent()
    {
        $path = $this->eventKey; // 跳转地址 pages/home/index
    }

    private function locationSelectEvent()
    {
        // 地址详细坐标在 LocationMessageHandler 类
    }

    private function templateEvent()
    {
        if ($this->message['Status'] == 'success') {
            // 推送成功
        }
    }

}
