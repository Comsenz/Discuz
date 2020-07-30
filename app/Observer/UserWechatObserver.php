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

use App\Models\UserWechat;
use App\User\AvatarUploader;
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
     * @var AvatarUploader
     */
    protected $uploader;

    /**
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     * @param AvatarUploader $uploader
     */
    public function __construct(Filesystem $filesystem, SettingsRepository $settings, AvatarUploader $uploader)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->uploader = $uploader;
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
        $avatar = $this->uploader->getAvatarPath($user);

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
        $user->changeAvatar($avatar, $isRemote);

        $user->save();
    }
}
