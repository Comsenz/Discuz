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

namespace App\User;

use App\Censor\Censor;
use App\Exceptions\UploadException;
use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Intervention\Image\Image;

class AvatarUploader
{
    /**
     * @var Censor
     */
    protected $censor;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Censor $censor
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     */
    public function __construct(Censor $censor, Filesystem $filesystem, SettingsRepository $settings)
    {
        $this->censor = $censor;
        $this->filesystem = $filesystem;
        $this->settings = $settings;
    }

    /**
     * @param User $user
     * @param Image $image
     * @throws UploadException
     */
    public function upload(User $user, Image $image)
    {
        if (extension_loaded('exif')) {
            $image->orientate();
        }

        $encodedImage = $image->fit(200, 200)->encode('png')->save();

        // 检测敏感图
        $this->censor->checkImage($image->dirname .'/'. $image->basename);

        if ($this->censor->isMod) {
            throw new UploadException();
        }

        $avatarPath = $this->getAvatarPath($user);

        // 判断是否开启云储存
        if ($this->settings->get('qcloud_cos', 'qcloud')) {
            $user->changeAvatar($avatarPath, true);

            $avatarPath = 'public/avatar/' . $avatarPath;
        } else {
            $user->changeAvatar($avatarPath);
        }

        $this->filesystem->put($avatarPath, $encodedImage);
    }

    /**
     * 删除头像
     *
     * @param User $user
     */
    public function remove(User $user)
    {
        $avatarPath = $user->getRawOriginal('avatar');

        $user->saved(function () use ($avatarPath) {
            $this->deleteFile($avatarPath);
        });

        $user->changeAvatar(null);
    }

    /**
     * 上传失败则删除 本地/COS 图片资源
     *
     * @param $avatarPath
     */
    public function deleteFile($avatarPath)
    {
        // 判断是否是cos地址
        if (strpos($avatarPath, '://') === false) {
            if ($this->filesystem->has($avatarPath)) {
                $this->filesystem->delete($avatarPath);
            }
        } else {
            $cosPath = 'public/avatar/' . Str::after($avatarPath, '://');
            // 判断是否关闭了腾讯COS
            if ($this->settings->get('qcloud_cos', 'qcloud')) {
                $this->filesystem->delete($cosPath);
            } else {
                app(Factory::class)->disk('avatar_cos')->delete($cosPath);
            }
        }
    }

    /**
     * @param User $user
     * @return string
     */
    public function getAvatarPath(User $user)
    {
        $uid = sprintf('%09d', $user->id);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'.png';
    }
}
