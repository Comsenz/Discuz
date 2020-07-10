<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\User;

use App\Censor\Censor;
use App\Exceptions\UploadException;
use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Illuminate\Contracts\Filesystem\Filesystem;

class AvatarUploader
{
    protected $filesystem;

    protected $censor;

    protected $app;

    /**
     * 图片名称
     *
     * @var
     */
    public $avatarPath;

    public $settings;

    public $url;

    public function __construct(Filesystem $filesystem, Censor $censor, Application $app, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->censor = $censor;
        $this->app = $app;
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->url = $url;
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

        $encodedImage = $image->fit(200, 200)->encode('png');

        // 检测敏感图
        $this->censor->checkImage($image->dirname .'/'. $image->basename);
        if ($this->censor->isMod) {
            throw new UploadException();
        }

        $this->avatarPath = $user->id . '.png';

        // 判断是否开启云储存
        if ($this->settings->get('qcloud_cos', 'qcloud')) {
            $user->changeAvatar('cos://' . $this->avatarPath);
            $this->avatarPath = 'public/avatar/' . $this->avatarPath; // 云目录
        } else {
            $user->changeAvatar($this->avatarPath);
        }

        $this->filesystem->put($this->avatarPath, $encodedImage);
    }

    /**
     * 删除头像
     *
     * @param User $user
     */
    public function remove(User $user)
    {
        $avatarPath = $user->getRawOriginal('avatar');

        // save后事件
        $user->saved(function () use ($avatarPath) {
            $this->deleteFile($avatarPath);
        });

        $user->changeAvatar('');
        $user->avatar_at = null;
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
                $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
            }
        }
    }
}
