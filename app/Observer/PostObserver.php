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
        $this->refreshSitePostCount();
    }

    /**
     * @param Post $post
     */
    public function updated(Post $post)
    {
        if ($post->wasChanged(['is_approved', 'deleted_at'])) {
            if ($post->is_first) {
                $post->thread->is_approved = $post->is_approved;
                $post->thread->deleted_at = $post->deleted_at;
                $post->thread->deleted_user_id = $post->deleted_user_id;

                $post->thread->save();
            }

            $this->refreshSitePostCount();
        }
    }

    /**
     * @param Post $post
     */
    public function deleted(Post $post)
    {
        $this->refreshSitePostCount();
    }

    /**
     * 刷新站点回复数
     */
    private function refreshSitePostCount()
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
