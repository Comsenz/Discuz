<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Observer;

use App\Exceptions\TranslatorException;
use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;

class UserObserver
{
    protected $settings;

    protected $app;

    public function __construct(SettingsRepository $settings, Application $app)
    {
        $this->settings = $settings;
        $this->app = $app;
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

    /**
     * 管理组用户不允许删除
     * @param User $user
     * @throws TranslatorException
     */
    public function deleting(User $user)
    {
        if ($user->isAdmin()) {
            throw new TranslatorException('user_delete_group_error');
        }
    }

    /**
     * @param User $user
     */
    public function deleted(User $user)
    {
        //删除用户头像
        $img = $user->id . '.png';

        if (strpos($user->avatar, '://') === false) {
            $this->app->make(Factory::class)->disk('avatar')->delete($img);
        } else {
            $cosPath = 'public/avatar/' . $img;
            $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
        }
    }
}
