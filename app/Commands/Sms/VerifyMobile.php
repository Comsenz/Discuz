<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Sms;

use App\Api\Controller\Mobile\VerifyController;
use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterPhoneUser;
use App\Events\Users\Logind;
use App\Models\MobileCode;
use App\Models\User;
use App\Models\UserWalletFailLogs;
use App\Repositories\MobileCodeRepository;
use App\Validators\UserValidator;
use Discuz\Api\Client;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class VerifyMobile
{
    use EventsDispatchTrait;

    protected $controller;

    protected $mobileCode;

    protected $apiClient;

    protected $actor;

    protected $bus;

    protected $params;

    protected $validator;

    protected $mobileCodeRepository;

    protected $events;

    public function __construct(VerifyController $controller, MobileCode $mobileCode, User $actor, $params = [])
    {
        $this->controller = $controller;
        $this->mobileCode = $mobileCode;
        $this->actor = $actor;
        $this->params = $params;
    }

    public function handle(Client $apiClient, Dispatcher $bus, UserValidator $validator, MobileCodeRepository $mobileCodeRepository, Events $events)
    {
        $this->apiClient = $apiClient;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->mobileCodeRepository = $mobileCodeRepository;
        $this->events = $events;
        return call_user_func([$this, Str::camel($this->mobileCode->type)]);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function login()
    {
        //register new user
        if (is_null($this->mobileCode->user)) {
            $data['register_ip'] = Arr::get($this->params, 'ip');
            $data['register_port'] = Arr::get($this->params, 'port');
            $data['mobile'] = $this->mobileCode->mobile;
            $user = $this->bus->dispatch(
                new RegisterPhoneUser($this->actor, $data)
            );
            $this->mobileCode->setRelation('user', $user);
        }

        $this->events->dispatch(
            new Logind($this->mobileCode->user)
        );
        //login
        $this->controller->serializer = TokenSerializer::class;
        $params = [
            'username' => $this->mobileCode->user->username,
            'password' => ''
        ];

        $response = $this->bus->dispatch(
            new GenJwtToken($params)
        );

        return json_decode($response->getBody());

    }

    protected function bind()
    {
        $mobile = $this->mobileCode->mobile;

        // 判断手机号是否已经被绑定
        if ($this->actor->mobile) {
            throw new \Exception('mobile_is_already_bind');
        }

        $this->controller->serializer = UserSerializer::class;
        if ($this->actor->exists) {
            $this->actor->changeMobile($mobile);
            $this->actor->save();
            $this->mobileCode->user = $this->actor;
        }
        return $this->mobileCode->user;
    }

    protected function rebind()
    {
        $mobile = $this->mobileCode->mobile;

        $this->controller->serializer = UserSerializer::class;
        if ($this->actor->exists) {
            // 删除验证身份的验证码
            MobileCode::where('mobile', $this->actor->getRawOriginal('mobile'))
                ->where('type', 'verify')
                ->where('state', 1)
                ->where('updated_at', '<', Carbon::now()->addMinutes(10))
                ->delete();

            $this->actor->changeMobile($mobile);
            $this->actor->save();
            $this->mobileCode->user = $this->actor;
        }
        return $this->mobileCode->user;
    }

    protected function resetPwd()
    {
        $this->controller->serializer = UserSerializer::class;
        if ($this->mobileCode->user && isset($this->params['password'])) {
            $this->validator->valid([
                'password' => $this->params['password']
            ]);
            $this->mobileCode->user->changePassword($this->params['password']);
            $this->mobileCode->user->save();
        }
        return $this->mobileCode->user;
    }

    protected function resetPayPwd()
    {
        $this->controller->serializer = UserSerializer::class;
        if ($this->mobileCode->user && isset($this->params['pay_password'])) {
            $this->validator->valid([
                'pay_password' => $this->params['pay_password'],
                'pay_password_confirmation' => $this->params['pay_password_confirmation'],
            ]);
            $this->mobileCode->user->changePayPassword($this->params['pay_password']);
            $this->mobileCode->user->save();

            // 清空支付密码错误次数
            UserWalletFailLogs::deleteAll($this->mobileCode->user->id);
        }
        return $this->mobileCode->user;
    }

    protected function verify()
    {
        $this->controller->serializer = UserSerializer::class;
        return $this->mobileCode->user;
    }
}
