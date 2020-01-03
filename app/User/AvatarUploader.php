<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\User;

use App\Censor\Censor;
use App\Exceptions\UploadException;
use App\Models\User;
use Discuz\Foundation\Application;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Illuminate\Contracts\Filesystem\Filesystem;

class AvatarUploader
{
    protected $file;

    protected $censor;

    protected $app;

    /**
     * 图片名称
     *
     * @var
     */
    public $avatarPath;

    public function __construct(Filesystem $file, Censor $censor, Application $app)
    {
        $this->file = $file;
        $this->censor = $censor;
        $this->app = $app;
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

        $this->avatarPath = $user->id . '.png';

        $user->changeAvatar($this->avatarPath);

        $this->file->put($this->avatarPath, $encodedImage);

        // 检测敏感图
        $this->censor->checkImage($this->getPathname());
        if ($this->censor->isMod) {
            $this->deleteFile($this->avatarPath);
            throw new UploadException();
        }
    }

    /**
     * 上传失败则删除本地图片资源
     *
     * @param $avatarPath
     */
    public function deleteFile($avatarPath)
    {
        if ($this->file->has($avatarPath)) {
            $this->file->delete($avatarPath);
        }
    }

    /**
     * get file path name
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->app->config('filesystems.disks.avatar.root') . '/' . $this->avatarPath;
    }
}
