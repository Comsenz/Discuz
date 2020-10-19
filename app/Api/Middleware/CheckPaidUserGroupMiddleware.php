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

namespace App\Api\Middleware;

use App\Events\Group\PaidGroup;
use App\Models\Group;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use App\Models\GroupPaidUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckPaidUserGroupMiddleware implements MiddlewareInterface
{
    public $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $actor = $request->getAttribute('actor');

        if ($actor->groups->count() && !$actor->isGuest()) {
            //检查到期付费用户组
            $groups = $actor->groups()->where('is_paid', Group::IS_PAID)->get();

            if ($groups->count()) {
                $now = Carbon::now();
                foreach ($groups as $group => $group_item) {
                    if (empty($group_item->pivot->expiration_time)) {
                        //免费组变为收费组
                        $this->events->dispatch(
                            new PaidGroup($group_item->id, $actor)
                        );
                    } elseif ($group_item->pivot->expiration_time < $now) {
                        GroupPaidUser::where('group_id', $group_item->pivot->group_id)
                            ->where('user_id', $group_item->pivot->user_id)
                            ->update(['deleted_at' => $now, 'delete_type' => GroupPaidUser::DELETE_TYPE_EXPIRE]);
                        $actor->groups()->detach($group_item);
                    }
                }
            }
        }

        return $handler->handle($request);
    }
}
