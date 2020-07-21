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

use App\Models\Dialog;
use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchCreateDialog
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var
     */
    public $attributes;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $attributes
     */
    public function __construct(User $actor, $attributes)
    {
        $this->actor = $actor;
        $this->attributes = $attributes;
    }

    public function handle(Dialog $dialog, UserRepository $user, Dispatcher $events, DispatcherBus $bus)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        if (! $this->actor->can('dialog.create')) {
            $result['meta'][] = ['message' => 'permission_denied'];
            return $result;
        }

        $sender = $this->actor->id;
        $recipients = explode(',', Arr::get($this->attributes, 'recipient_username'));

        foreach ($recipients as $recipient) {
            $recipientUser = $user->query()->where('username', $recipient)->first();

            //处理错误的用户名
            if (!$recipientUser) {
                $result['meta'][] = ['name' => $recipient, 'message' => ' not found '];
                continue;
            }
            if ($sender == $recipientUser->id) {
                $result['meta'][] = ['name' => $recipient, 'message' => ' permission denied '];
                continue;
            }

            //在黑名单中，不能创建会话
            if (in_array($sender, array_column($recipientUser->deny->toArray(), 'id'))) {
                $result['meta'][] = ['name' => $recipient, 'message' => ' user deny '];
                continue;
            }

            $result['data'][] = $dialogRes = $dialog::buildOrFetch($sender, $recipientUser->id);

            //创建会话时如传入消息内容，则创建消息
            $message_text = Arr::get($this->attributes, 'message_text', null);
            if ($message_text) {
                $this->attributes['dialog_id'] = $dialogRes->id;
                $bus->dispatchNow(
                    new CreateDialogMessage($this->actor, $this->attributes)
                );
            }
        }
        return $result;
    }
}
