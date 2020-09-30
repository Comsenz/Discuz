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

namespace App\Commands\Dialog;

use App\Models\Attachment;
use App\Models\User;
use App\Repositories\DialogMessageRepository;
use App\Repositories\DialogRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteDialog
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var
     */
    public $id;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $id
     */
    public function __construct(User $actor, $id)
    {
        $this->actor = $actor;
        $this->id = $id;
    }

    public function handle(DialogRepository $dialogs, DialogMessageRepository $dialogMessages, Dispatcher $events, DispatcherBus $bus)
    {
        $this->events = $events;

        $dialog = $dialogs->findOrFail($this->id, $this->actor);
        if ($dialog->sender_user_id == $this->actor->id) {
            $actorType = 'sender';
            $otherType = 'recipient';
        } else {
            $actorType = 'recipient';
            $otherType = 'sender';
        }

        //增加删除时间，供获取接口筛选
        $dialog->{$actorType . '_deleted_at'} = Carbon::now();
        $dialog->save();

        //双方都存在删除动作时，删除部分无效消息
        if ($dialog->sender_deleted_at && $dialog->recipient_deleted_at) {
            $dateTime = $dialog->{$actorType . '_deleted_at'} > $dialog->{$otherType . '_deleted_at'} ?
                $dialog->{$otherType . '_deleted_at'} :
                $dialog->{$actorType . '_deleted_at'};

            $query = $dialogMessages->query()
                ->where('dialog_id', $dialog->id)
                ->where('created_at', '<', $dateTime);
            $dialogMessage = $query->get();
            $query->delete();

            Attachment::query()
                ->whereIn('id', $dialogMessage->pluck('attachment_id'))
                ->update(['type_id'=>0]);

        }

        return $dialog;
    }
}
