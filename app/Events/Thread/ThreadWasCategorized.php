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

namespace App\Events\Thread;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;

class ThreadWasCategorized
{
    /**
     * @var Thread
     */
    public $thread;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var Category
     */
    public $newCategory;

    /**
     * @var Category
     */
    public $oldCategory;

    /**
     * @param Thread $thread
     * @param User $actor
     * @param Category $newCategory
     * @param Category|null $oldCategory
     */
    public function __construct(Thread $thread, User $actor, Category $newCategory, Category $oldCategory = null)
    {
        $this->thread = $thread;
        $this->actor = $actor;
        $this->newCategory = $newCategory;
        $this->oldCategory = $oldCategory;
    }
}
