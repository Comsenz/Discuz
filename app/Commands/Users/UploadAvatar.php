<?php


namespace App\Commands\Users;


use App\Models\User;
use App\Repositories\UserRepository;
use App\User\AvatarUploader;
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
    protected $upload_file;

    protected $avatarUploader;

    public function __construct(User $actor, $id, UploadedFile $upload_file)
    {
        $this->actor = $actor;
        $this->id = $id;
        $this->upload_file = $upload_file;
    }

    public function handle(Application $app, UserRepository $users, AvatarValidator $validator, AvatarUploader $avatarUploader) {

        $this->app = $app;
        $this->users = $users;
        $this->validator = $validator;
        $this->avatarUploader = $avatarUploader;

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

            $this->avatarUploader->upload($user, $image);

            $user->save();
        } finally {
            @unlink($tmpFile);
        }

        return $user;
    }
}
