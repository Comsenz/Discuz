<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Exceptions\TranslatorException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;

class UpdateUser
{
    use AssertPermissionTrait;

    protected $id;

    protected $data;

    protected $actor;

    protected $users;

    protected $validator;

    public function __construct($id, $data, User $actor)
    {
        $this->id = $id;
        $this->data = $data;
        $this->actor = $actor;
    }

    public function handle(UserRepository $users, UserValidator $validator)
    {
        $this->users = $users;
        $this->validator = $validator;
        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws TranslatorException
     */
    public function __invoke()
    {
        $user = $this->users->findOrFail($this->id, $this->actor);

        $isSelf = $this->actor->id === $user->id;

        if (!$isSelf) {
            $this->assertCan($this->actor, 'edit', $user);
        }

        $validator = [];

        $attributes = Arr::get($this->data, 'data.attributes');

        if ($newPassword = Arr::get($attributes, 'newPassword')) {
            if ($isSelf) {
                $verifyPwd = $user->checkPassword(Arr::get($attributes, 'password'));
                if (!$verifyPwd) {
                    throw new TranslatorException('user_update_error', ['not_match_used_password']);
                }

                $this->validator->setUser($user);
                $validator['password_confirmation'] = Arr::get($attributes, 'password_confirmation');
            }
            $user->changePassword($newPassword);
            $validator['password'] = $newPassword;
        }

        if ($mobile = Arr::get($attributes, 'mobile')) {
            $this->assertCan($this->actor, 'edit.mobile', $user);
            $user->changeMobile($mobile);
        }

        if (Arr::has($attributes, 'status')) {
            $this->assertCan($this->actor, 'edit.status', $user);
            $user->changeStatus(Arr::get($attributes, 'status'));
        }

        if ($groupId = Arr::get($attributes, 'groupId')) {
            $this->assertCan($this->actor, 'edit.group', $user);
            $user->groups()->sync($groupId);
        }

        $this->validator->valid($validator);

        $user->save();

        return $user;
    }
}
