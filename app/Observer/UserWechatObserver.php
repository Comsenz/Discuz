<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Observer;

use App\Exceptions\TranslatorException;
use App\Models\UserWechat;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Filesystem\CosAdapter;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserWechatObserver
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     */
    public function __construct(Filesystem $filesystem, SettingsRepository $settings)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
    }

    /**
     * @param UserWechat $userWechat
     */
    public function created(UserWechat $userWechat)
    {
        $this->avatarSync($userWechat);
    }

    /**
     * @param UserWechat $userWechat
     */
    public function updated(UserWechat $userWechat)
    {
        $this->avatarSync($userWechat);
    }

    /**
     * 同步微信头像
     *
     * @param UserWechat $userWechat
     */
    public function avatarSync($userWechat)
    {
        // 是否存在微信头像
        if (empty($userWechat->headimgurl)) {
            return;
        }

        $user = $userWechat->user;

        // 是否未绑定用户 或 用户已设置头像
        if (empty($user) || $user->avatar_at) {
            return;
        }

        // 获取微信头像
        $response = (new Client())->request('get', $userWechat->headimgurl);

        if ($response->getStatusCode() != 200) {
            return;
        }

        // 微信头像二进制内容
        $img = $response->getBody()->getContents();

        // 是否云存储
        $isRemote = $this->filesystem->getAdapter() instanceof CosAdapter;

        // 头像名称
        $avatar = $user->id . '.png';

        /**
         * 保存头像：开启云存储时，使用磁盘 avatar_cos，否则使用磁盘 avatar
         * @see SettingsServiceProvider boot()
         */
        $this->filesystem->put(($isRemote ? 'public/avatar/' : '') . $avatar, $img);

        /**
         * 修改用户信息
         *
         * 因为之前开启 cos 时用户头像存的完整 url，获取头像地址是通过 '://' 判断
         * 所以这里依旧给 cos 头像拼接 '://' 以作区分
         * @see \App\Models\User getAvatarAttribute()
         */
        $user->changeAvatar(($isRemote ? '://' : '') . $avatar);
        $user->avatar_at = Carbon::now();

        $user->save();
    }
}
