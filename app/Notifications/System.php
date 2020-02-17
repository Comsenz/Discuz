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

    protected $tplIds = [
        'ban' => 10,
        'normal' => 11,
        'mod_normal' => 2,
        'mod' => 3
    ];

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
        if($this->message instanceof StatusMessage) {
            $actionType = User::enumStatus($notifiable->status);
            if($this->isMod()) {
                $notifiable->status == 0 && $actionType = 'mod_'.$actionType;
            }
            $tplId = Arr::get($this->tplIds, $actionType);
        }

        $this->getTplData($tplId);

        $this->message->setTplData($this->tplData);

        //开启状态发送系统消息
        if(!is_null($this->tplData) && $this->tplData->status == NotificationTpl::OPEN) {
            return ['database'];
        }
        return [];
    }

    public function toDatabase($notifiable)
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
}
