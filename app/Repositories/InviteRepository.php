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

use App\Models\Invite;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class InviteRepository extends AbstractRepository
{
    /**
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return Invite::query();
    }

    /**
     * @param int $id
     * @param User|null $actor
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = self::query()->where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }

    /**
     * Verify the invitation code is available
     *
     * @param $code
     * @return mixed
     */
    public function verifyCode($code)
    {
        return self::query()->where([
            ['code', '=', $code],
            ['to_user_id', '=', '0'],
            ['endtime', '>', time()],
            ['status', '=', '1']
        ])->first();
    }

    /**
     * 是否是管理员长度
     *
     * @param $code
     * @return bool
     */
    public function lengthByAdmin($code)
    {
        return Invite::lengthByAdmin($code);
    }
}
