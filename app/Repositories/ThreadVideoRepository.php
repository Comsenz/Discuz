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

use App\Models\ThreadVideo;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ThreadVideoRepository
 * @package App\Repositories
 *
 */
class ThreadVideoRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the user login log table.
     *
     * @return Model|Builder
     */
    public function query()
    {
        return ThreadVideo::query();
    }

    public function findOrFailByFileId($file_id)
    {
        return $this->query()
            ->where('file_id', $file_id)
            ->firstOrFail();
    }

    public function findOrFailByThreadId($file_id)
    {
        return $this->query()
            ->where('thread_id', $file_id)
            ->firstOrFail();
    }
}
