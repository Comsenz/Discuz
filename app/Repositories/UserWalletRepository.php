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

use App\Models\UserWallet;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserWalletRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserWallet::query();
    }

    public function findOrFail($id, User $actor = null)
    {
        $query = UserWallet::where('user_id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
