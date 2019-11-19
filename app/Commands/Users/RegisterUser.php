<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: RegisterUser.php xxx 2019-11-11 18:22:00 LiuDongdong $
 */

namespace App\Commands\Users;

use App\Events\Users\Registered;
use App\Events\Users\Saving;
use App\Models\User;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class RegisterUser
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new user.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new user.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param SettingsRepository $settings
     * @param UserValidator $validator
     * @return User
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, SettingsRepository $settings, UserValidator $validator)
    {
        $this->events = $events;

        // 是否开放注册
        // if (! $settings->get('allow_sign_up')) {
        //     $this->assertAdmin($this->actor);
        // }

        $password = Arr::get($this->data, 'password');

        // 如果提供了有效的身份验证令牌作为属性，那么我们将不要求用户选择密码。
        // if (isset($data['attributes']['token'])) {
        //     $token = RegistrationToken::validOrFail($data['attributes']['token']);
        //
        //     $password = $password ?: Str::random(20);
        // }

        $user = User::register($this->data);

        $user->raise(new Registered($user, $this->actor, $this->data));

        $this->events->dispatch(
            new Saving($user, $this->actor, $this->data)
        );

        $validator->valid(array_merge($user->getAttributes(), compact('password')));

        $user->save();

        $this->dispatchEventsFor($user, $this->actor);

        return $user;
    }
}
