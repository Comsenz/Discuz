<?php


namespace App\Commands\Users;


use App\Models\User;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use Illuminate\Support\Arr;

class UpdateUser
{
    use AssertPermissionTrait;

    protected $id;

    protected $data;

    protected $actor;

    public function __construct($id, $data, User $actor)
    {
        $this->id = $id;
        $this->data = $data;
        $this->actor = $actor;
    }

    public function handle(UserRepository $users, UserValidator $userValidator) {
        return $this($users, $userValidator);
    }

    /**
     * @param UserRepository $users
     * @param UserValidator $userValidator
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function __invoke(UserRepository $users, UserValidator $userValidator)
    {

        $data = null;
        $id = $this->id;

        try {
            $user = $users->findOrFail($id, $this->actor);

            $isSelf = $this->actor->id === $user->id;

            if (!$isSelf) {
                $this->assertCan($this->actor, 'edit', $user);
            }

            $validator = [];

            $attributes = Arr::get($this->data, 'data.attributes');

            if ($newPassword = Arr::get($attributes, 'newPassword')) {

                if ($isSelf) {
                    $verifyPwd = $user->checkPassword(Arr::get($attributes, 'password'));
                    $this->assertPermission($verifyPwd);

                    $userValidator->setUser($user);
                    $validator['password_confirmation'] = Arr::get($attributes, 'password_confirmation');
                }
                $user->changePassword($newPassword);
                $validator['password'] = $newPassword;
            }

            if ($mobile = Arr::get($this->data, 'data.attributes.mobile')) {
                $user->changeMobile($mobile);
            }

            if ($status = Arr::get($this->data, 'data.attributes.status')) {
                $this->assertCan($this->actor, 'edit.status', $user);
                $user->changeStatus($status);
            }

            if ($groupId = Arr::get($this->data, 'data.attributes.groupId')) {
                $this->assertCan($this->actor, 'edit.group', $user);
                $user->groups()->sync($groupId);
            }

            if($action = Arr::get($this->data, 'data.attributes.action')) {
                dd($action);
                if($isSelf) {
                    $user->wechat->delete();
                }
            }

            $userValidator->valid($validator);

            $user->save();

            $user->succeed = true;
            $data = $user;
        } catch (Exception $e) {
            $data = new User(compact('id'));
            $data->error = $e->getMessage();
        }

        return $data;
    }
}
