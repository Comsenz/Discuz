<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Events\Users\Registered;
use App\Events\Users\Saving;
use App\Models\User;
use App\Validators\UserValidator;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class RegisterUser
{
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

        $password = Arr::get($this->data, 'password');
        $password_confirmation = Arr::get($this->data, 'password_confirmation');

        $user = User::register(Arr::only($this->data, ['username', 'password', 'register_ip']));

        // 付费模式，默认注册时即到期
        if ($settings->get('site_mode') == 'pay') {
            $user->expired_at = Carbon::now();
        }

        $user->raise(new Registered($user, $this->actor, $this->data));

        $this->events->dispatch(
            new Saving($user, $this->actor, $this->data)
        );

        //使用该验证可不传 password_confirmation参数不检测
        $validator->valid(array_merge($user->getAttributes(), compact('password', 'password_confirmation')));

        $user->save();

        $this->dispatchEventsFor($user, $this->actor);

        return $user;
    }
}
