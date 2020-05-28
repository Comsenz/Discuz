<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\MessageTemplate\StatusMessage;
use App\MessageTemplate\Wechat\WechatStatusMessage;
use App\MessageTemplate\Wechat\WechatWithdrawalMessage;
use App\MessageTemplate\WithdrawalMessage;
use App\Models\NotificationTpl;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

/**
 * 系统通知
 *
 * Class System
 * @package App\Notifications
 */
class System extends Notification
{
    protected $data;

    protected $type;

    protected $tplData;

    protected $message;

    protected $settings;

    /**
     * System constructor.
     *
     * @param $type
     * @param array $data
     */
    public function __construct($type, $data = [])
    {
        $this->type = $type;
        $this->data = $data;

        $this->message = app()->make($type);
        $this->settings = app()->make(SettingsRepository::class);
    }

    public function via($notifiable)
    {
        $tplId = $this->message->getTplId();

        $this->specialNotice($notifiable, $tplId);

        // set tplData
        $this->getTplData($tplId);

        $this->message->setTplData($this->tplData);

        // 开启状态发送系统消息
        if (!is_null($this->tplData) && $this->tplData->status == NotificationTpl::OPEN) {
            return (array)NotificationTpl::enumType($this->tplData->type);
        }

        return [];
    }

    public function toDatabase($notifiable)
    {
        return $this->message->notifiable($notifiable)->template($this->data);
    }

    public function toWechat($notifiable)
    {
        return $this->message->notifiable($notifiable)->template($this->data);
    }

    public function getTplData($id)
    {
        return $this->tplData ? $this->tplData : $this->tplData = NotificationTpl::find($id);
    }

    protected function isMod()
    {
        return (bool)$this->settings->get('register_validate');
    }

    /**
     * 特殊处理通知类
     *
     * @param $notifiable
     * @param $tplId
     */
    protected function specialNotice($notifiable, &$tplId)
    {
        if ($this->message instanceof StatusMessage) {
            $tplId = $this->discTpl($notifiable->status, $notifiable->getRawOriginal('status'));
        }

        if ($this->message instanceof WechatStatusMessage) {
            $tplId = $this->discTpl($notifiable->status, $notifiable->getRawOriginal('status'), 1);
        }

        if ($this->message instanceof WithdrawalMessage || $this->message instanceof WechatWithdrawalMessage) {
            $cashStatus = Arr::get($this->data, 'cash_status'); // 获取提现状态
            $type = 0;
            if ($this->message instanceof WechatWithdrawalMessage) {
                $type = 1;
            }
            $tplId = $this->cashTpl($cashStatus, $type);
        }
    }

    /**
     * 区分通知
     * (审核中变为正常 和 禁用中变为正常)
     *
     * @param $status
     * @param $originStatus
     * @param int $type 0系统 1微信
     * @return int
     */
    public function discTpl($status, $originStatus, $type = 0)
    {
        $id = 0;
        if ($status == $originStatus) {
            return $id;
        }

        if ($status == 0) {
            if ($originStatus == 1) {
                $id = 11; // 帐号解除禁用通知
            } else {
                $id = 2; // 审核通过通知
            }
        } else {
            if ($originStatus == 0 && $status == 1) {
                $id = 10; // 帐号禁用通知
            } elseif ($originStatus == 2 && $status == 3) { // 2审核中 变 审核拒绝
                $id = 3; // 审核拒绝通知
            } else {
                $id = 0; // 错误状态下：2审核中变成禁用等 是不允许的
            }
        }

        // 判断和系统通知的对应微信通知关系
        if ($type == 1) {
            $wechat = [
                11 => 23,
                2 => 14,
                10 => 22,
                3 => 15,
            ];
            if (array_key_exists($id, $wechat)) {
                $id = $wechat[$id];
            }
        }

        // 设值tplID
        $this->message->setTplId($id);

        return $id;
    }

    /**
     * @param int $status 提现状态：1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败
     * @param int $type 0系统 1微信
     * @return int
     */
    public function cashTpl($status, $type = 0)
    {
        $noReason = [1, 2, 4, 5];
        if (!$type) {
            if (in_array($status, $noReason)) {
                $id = 1;
            } else {
                $id = 2;
            }
        } else {
            if (in_array($status, $noReason)) {
                $id = 1;
            } else {
                $id = 2;
            }
        }

        $id = 0;
        return $id;
    }
}
