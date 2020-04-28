<?php


namespace App\Observer;


use App\Models\Post;
use Discuz\Contracts\Setting\SettingsRepository;

class PostObserver
{
    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 处理 Post「created」事件
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post)
    {
        $this->settings->set('post_count', Post::where('is_approved', Post::APPROVED)->whereNull('deleted_at')->count());
    }

}
