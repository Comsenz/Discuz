<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Exceptions\UploadException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\User\AvatarUploader;
use App\Validators\AvatarValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadAvatar
{
    use AssertPermissionTrait;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var UploadedFileInterface
     */
    public $avatar;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var AvatarUploader
     */
    protected $uploader;

    /**
     * @var AvatarValidator
     */
    protected $validator;

    /**
     * @param int $userId The ID of the user to upload the avatar for.
     * @param UploadedFileInterface $avatar The avatar file to upload.
     * @param User $actor The user performing the action.
     */
    public function __construct($userId, UploadedFileInterface $avatar, User $actor)
    {
        $this->userId = $userId;
        $this->avatar = $avatar;
        $this->actor = $actor;
    }

    /**
     * @param UserRepository $users
     * @param AvatarUploader $uploader
     * @param AvatarValidator $validator
     * @return User|mixed
     * @throws PermissionDeniedException
     * @throws UploadException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(UserRepository $users, AvatarUploader $uploader, AvatarValidator $validator)
    {
        $this->users = $users;
        $this->uploader = $uploader;
        $this->validator = $validator;

        return $this();
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     * @throws UploadException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke()
    {
        $user = $this->users->findOrFail($this->userId);

        if ($this->actor->id !== $user->id) {
            $this->assertCan($this->actor, 'edit', $user);
        }

        $ext = pathinfo($this->avatar->getClientFilename(), PATHINFO_EXTENSION);
        $ext = $ext ? ".$ext" : '';

        $tmpFile = tempnam(storage_path('/tmp'), 'avatar');
        $tmpFileWithExt = $tmpFile . $ext;

        $this->avatar->moveTo($tmpFileWithExt);

        try {
            $file = new SymfonyUploadedFile(
                $tmpFileWithExt,
                $this->avatar->getClientFilename(),
                $this->avatar->getClientMediaType(),
                $this->avatar->getError(),
                true
            );

            $this->validator->valid(['avatar' => $file]);

            $image = (new ImageManager())->make($tmpFileWithExt);

            $this->uploader->upload($user, $image);

            $user->save();
        } finally {
            @unlink($tmpFile);
            @unlink($tmpFileWithExt);
        }

        return $user;
    }
}
