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

namespace App\Observer;

use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;

class ThreadObserver
{
    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Thread $thread
     */
    public function created(Thread $thread)
    {
        $this->refreshSiteThreadCount();
    }

    /**
     * @param Thread $thread
     */
    public function updated(Thread $thread)
    {
        if ($thread->wasChanged(['is_approved', 'deleted_at'])) {
            $thread->firstPost->is_approved = $thread->is_approved;
            $thread->firstPost->deleted_at = $thread->deleted_at;
            $thread->firstPost->deleted_user_id = $thread->deleted_user_id;

            $thread->firstPost->save();

            $this->refreshSiteThreadCount();
        }
    }

    /**
     * @param Thread $thread
     */
    public function deleted(Thread $thread)
    {
        $this->refreshSiteThreadCount();
    }

    /**
     * 刷新站点主题数
     */
    private function refreshSiteThreadCount()
    {
        $this->settings->set(
            'thread_count',
            Thread::query()
                ->where('is_approved', Thread::APPROVED)
                ->whereNull('deleted_at')
                ->whereNotNull('user_id')
                ->count()
        );
    }
}
