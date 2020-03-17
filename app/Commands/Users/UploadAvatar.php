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
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Nyholm\Psr7\UploadedFile;

class UploadAvatar
{
    use AssertPermissionTrait;

    protected $app;

    protected $validator;

    protected $users;

    protected $upload_file;

    protected $avatarUploader;

    protected $settings;

    protected $actor;

    protected $id;

    public function __construct(User $actor, $id, UploadedFile $upload_file)
    {
        $this->actor = $actor;
        $this->id = $id;
        $this->upload_file = $upload_file;
    }

    /**
     * @param Application $app
     * @param UserRepository $users
     * @param AvatarValidator $validator
     * @param AvatarUploader $avatarUploader
     * @param SettingsRepository $settings
     * @return mixed
     * @throws PermissionDeniedException
     * @throws UploadException
     */
    public function handle(Application $app, UserRepository $users, AvatarValidator $validator, AvatarUploader $avatarUploader, SettingsRepository $settings)
    {
        $this->app = $app;
        $this->users = $users;
        $this->validator = $validator;
        $this->avatarUploader = $avatarUploader;
        $this->settings = $settings;

        return $this();
    }

    /**
     * @return mixed
     * @throws UploadException
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $user = $this->users->findOrFail($this->id);

        /**
         * 检测是否开启头像过滤-开启后限制次数
         */
        if (!$this->actor->isAdmin()) {
            if ($this->settings->get('qcloud_cms_image', 'qcloud', 0)) {
                if (!empty($user->avatar_at)) {
                    if (Carbon::now() < Carbon::parse($user->avatar_at)->addDay()) {
                        throw new UploadException('upload_time_not_up');
                    }
                }
            }
        }

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
                $this->upload_file->getError(),
                true
            );

            $this->validator->valid(['avatar' => $file]);
            $image = (new ImageManager())->make($tmpFile);

            $this->avatarUploader->upload($user, $image);

            $user->avatar_at = Carbon::now()->toDateTimeString();

            $user->save();
        } finally {
            @unlink($tmpFile);
        }

        return $user;
    }
}
