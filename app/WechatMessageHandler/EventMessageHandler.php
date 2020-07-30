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

namespace App\WechatMessageHandler;

use App\Models\WechatOffiaccountReply;
use Discuz\Wechat\EasyWechatTrait;
use Discuz\Wechat\Offiaccount\MessageEventHandlerInterface;

class EventMessageHandler extends MessageEventHandlerInterface
{
    use EasyWechatTrait;

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

    public function __construct($app)
    {
        $this->message = $app->server->getMessage();
        // app('wechatOffiaccount')->info(self::class . ': ', (array)$this->message);

        $this->easyWechat = $this->offiaccount();

        $this->event = $this->message['Event'];
        $this->eventKey = $this->message['EventKey'];

        $toUserName = $this->message['ToUserName'];     // 开发者微信号
        $fromUserName = $this->message['FromUserName']; // 发送方帐号（一个OpenID）
        $createTime = $this->message['CreateTime'];     // 消息创建时间（整型）
    }

    public function handle($payload = null)
    {
        try {
            if ($this->isNewSubscribe()) {
                // 查询被关注回复
                $this->reply = WechatOffiaccountReply::where('type', 0)->first();

                if (!empty($this->reply)) {
                    $type = WechatOffiaccountReply::enumReplyType($this->reply->reply_type);
                    if (empty($type)) {
                        return $this->{'Error'}('公众号配置关注信息错误');
                    }

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
        } catch (\Exception $e) {
            $this->wechatDebugLog($e->getMessage());

            return $this->{'Text'}($this->error());
        }
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
