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

use App\Models\Dialog;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DialogRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return Dialog::query();
    }

    /**
     * @param $id
     * @param User|null $actor
     * @return Builder|Model|Dialog
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = $this->query()
              ->where('id', $id)
              ->where(function ($query) use ($actor) {
                  $query->where('sender_user_id', $actor->id)
                  ->orWhere('recipient_user_id', $actor->id);
              });

        return $query->firstOrFail();
    }
}
