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
use App\Models\Vote;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VoteOptionRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the topic table.
     *
     * @return Builder
     */
    public function query()
    {
        return Vote::query();
    }

    /**
     * Find a topic by ID
     *
     * @param $vote_id
     * @param $option_id
     * @return Builder|Model
     */
    public function findOrFail($vote_id, $option_id)
    {
        return $this->query()
            ->where('id', $option_id)
            ->where('vote_id', $vote_id)
            ->firstOrFail();
    }
}
