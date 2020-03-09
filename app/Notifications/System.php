<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\MessageTemplate\StatusMessage;
use App\Models\NotificationTpl;
use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class System extends Notification
{
    protected $data;

    protected $type;

    protected $tplData;

    protected $message;

    protected $settings;

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
        if ($this->message instanceof StatusMessage) {
            $tplId = $this->discTpl($notifiable->status, $notifiable->getRawOriginal('status'));
        }

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

    protected function getTplData($id)
    {
        return $this->tplData ? $this->tplData : $this->tplData = NotificationTpl::find($id);
    }

    protected function isMod()
    {
        return (bool)$this->settings->get('register_validate');
    }

    /**
     * 区分通知
     * (审核中变为正常 和 禁用中变为正常)
     *
     * @param $status
     * @param $originStatus
     * @return int
     */
    public function discTpl($status, $originStatus)
    {
        $id = 0;
        if ($status == $originStatus) {
            return $id;
        }

        if ($status == 0) {
            if ($originStatus == 1) {
                $id = 11; // 账号解除禁用通知
            } else {
                $id = 2; // 审核通过通知
            }
        } else {
            if ($originStatus == 0 && $status == 1) {
                $id = 10; // 账号禁用通知
            } elseif ($originStatus == 2 && $status == 3) { // 2审核中 变 审核拒绝
                $id = 3; // 审核拒绝通知
            }
        }

        return $id;
    }
}
