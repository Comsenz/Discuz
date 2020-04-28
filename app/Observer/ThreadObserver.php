<?php


namespace App\Observer;


use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;

class ThreadObserver
{
    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 处理 Thread「created」事件
     *
     * @param Thread $thread
     * @return void
     */
    public function created(Thread $thread)
    {
        $this->settings->set('thread_count', Thread::where('is_approved', Thread::APPROVED)->whereNull('deleted_at')->count());
    }

}
