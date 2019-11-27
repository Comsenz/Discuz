<?php


namespace App\User;


use App\Models\User;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Illuminate\Contracts\Filesystem\Filesystem;

class AvatarUploader
{
    protected $file;

    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    public function upload(User $user, Image $image) {
        if (extension_loaded('exif')) {
            $image->orientate();
        }

        $encodedImage = $image->fit(200, 200)->encode('png');

        $avatarPath = Str::random() . '.png';

        $this->remove($user);
        $user->changeAvatar($avatarPath);

        $this->file->put($avatarPath, $encodedImage);
    }

    public function remove(User $user) {
        $avatarPath = $user->getOriginal('avatar');

        $user->saved(function() use ($avatarPath) {
            if($this->file->has($avatarPath)) {
                $this->file->delete($avatarPath);
            }
        });

        $user->changeAvatar('');
    }
}
