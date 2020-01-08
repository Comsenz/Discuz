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
        // 检测敏感图
        $this->censor->checkImage($image->dirname .'/'. $image->basename);
        if ($this->censor->isMod) {
            throw new UploadException();
        }

        $encodedImage = $image->fit(200, 200)->encode('png');

        $this->avatarPath = $user->id . '.png';

        $user->changeAvatar($this->avatarPath);

        $this->file->put($this->avatarPath, $encodedImage);
    }
}
