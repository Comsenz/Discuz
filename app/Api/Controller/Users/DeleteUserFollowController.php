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

namespace App\Api\Controller\Users;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Users\DeleteUserFollow;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteUserFollowController extends AbstractDeleteController
{
    public $serializer = InfoSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $to_user_id = (int) Arr::get($request->getParsedBody(), 'data.attributes.to_user_id', 0);
//      删除我的粉丝时使用from删
        $from_user_id = (int) Arr::get($request->getParsedBody(), 'data.attributes.from_user_id', 0);

        $data = collect();
        $data->push($this->bus->dispatch(
            new DeleteUserFollow($actor, $to_user_id, $from_user_id)
        ));
        return $data;
    }
}
