<?php


namespace App\Observer;


use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;

class UserObserver
{

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 处理 User「created」事件
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        $this->settings->set('user_count', User::where('status', 0)->count());
    }

}
