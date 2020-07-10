<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
