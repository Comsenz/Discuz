<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Observer;

use App\Models\Post;
use Discuz\Contracts\Setting\SettingsRepository;

class PostObserver
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
     * @param Post $post
     */
    public function created(Post $post)
    {
        $this->refreshSiteThreadCount();
    }

    /**
     * @param Post $post
     */
    public function updated(Post $post)
    {
        if ($post->isDirty('is_approved')) {
            if ($post->is_first) {
                $post->thread->is_approved = $post->is_approved;
                $post->thread->save();
            }

            $this->refreshSiteThreadCount();
        }
    }

    /**
     * @param Post $post
     */
    public function deleted(Post $post)
    {
        $this->refreshSiteThreadCount();
    }

    /**
     * 刷新站点主题数
     */
    private function refreshSiteThreadCount()
    {
        $this->settings->set(
            'post_count',
            Post::query()
                ->where('is_approved', Post::APPROVED)
                ->whereNull('deleted_at')
                ->whereNotNull('user_id')
                ->count()
        );
    }
}
