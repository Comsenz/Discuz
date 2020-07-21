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

namespace App\Repositories;

use App\Models\User;
use App\Models\UserFollow;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserFollowRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserFollow::query();
    }

    public function findOrFail($to_user_id, $from_user_id, User $actor = null)
    {
        $query = $this->query();

        if ($to_user_id) {
            $query->where(['to_user_id'=>$to_user_id,'from_user_id'=>$actor->id]);
        } else {
            $query->where(['to_user_id'=>$actor->id,'from_user_id'=>$from_user_id]);
        }

        return $query->firstOrFail();
    }

    /**
     * 获取用户关注情况
     * @param $from_user_id
     * @param $to_user_id
     * @return int
     * @return null:自己 0：未关注 1：已关注 2：互相关注
     */
    public function findFollowDetail($from_user_id, $to_user_id)
    {
        $follow = 0;
        if (!$from_user_id || !$to_user_id) {
            return $follow;
        }

        //自己时返回null，方便前台不展示关注按钮等操作
        if ($from_user_id == $to_user_id) {
            return null;
        }

        $followRes = $this->query()->where(['to_user_id'=>$to_user_id,'from_user_id'=>$from_user_id])->first();

        if ($followRes) {
            $follow = 1;
        }
        if ($followRes && $followRes['is_mutual'] == UserFollow::MUTUAL) {
            $follow = 2;
        }

        return $follow;
    }
}
