<?php


namespace App\Commands\Users;


use App\Models\User;
use App\Repositories\UserRepository;
use App\Validators\AvatarValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Zend\Diactoros\UploadedFile;

class UploadAvatar
{
    use AssertPermissionTrait;

    protected $app;
    protected $validator;
    protected $users;
    protected $file;
    protected $upload_file;

    public function __construct(User $actor, $id, UploadedFile $upload_file)
    {
        $this->actor = $actor;
        $this->id = $id;
        $this->upload_file = $upload_file;
    }

    public function handle(Application $app, UserRepository $users, AvatarValidator $validator, Factory $file) {

        $this->app = $app;
        $this->users = $users;
        $this->validator = $validator;
        $this->file = $file->disk('avatar');

        return $this();
    }

    public function __invoke()
    {
        $user = $this->users->findOrFail($this->id);

        if ($this->actor->id !== $user->id) {
            $this->assertCan($this->actor, 'edit', $user);
        }

        $tmpFile = tempnam($this->app->storagePath().'/tmp', 'avatar');

        $this->upload_file->moveTo($tmpFile);

        try {
            $file = new SymfonyUploadedFile(
                $tmpFile,
                $this->upload_file->getClientFilename(),
                $this->upload_file->getClientMediaType(),
                $this->upload_file->getSize(),
                $this->upload_file->getError(),
                true
            );

            $this->validator->valid(['avatar' => $file]);

            $image = (new ImageManager())->make($tmpFile);

            if (extension_loaded('exif')) {
                $image->orientate();
            }

            $encodedImage = $image->fit(200, 200)->encode('png');

            $avatarPath = Str::random() . '.png';

            $this->remove($user);

            $user->avatar = $avatarPath;

            $this->file->put($avatarPath, $encodedImage);

            $user->save();
        } finally {
            @unlink($tmpFile);
        }

        return $user;
    }

    private function remove(User $user) {
        $avatarPath = $user->getOriginal('avatar');

        $user->saved(function() use ($avatarPath) {
            if($this->file->has($avatarPath)) {
                $this->file->delete($avatarPath);
            }
        });

        $user->avatar = null;
    }
}
