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
use App\Exceptions\SmsCodeVerifyException;
use App\Models\MobileCode;
use App\Models\User;
use App\Repositories\MobileCodeRepository;
use App\Validators\UserValidator;
use Discuz\Api\Client;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Carbon;

class VerifyMobile
{
    protected $controller;

    protected $mobileCode;

    protected $apiClient;

    protected $actor;

    protected $bus;

    protected $params;

    protected $validator;

    protected $mobileCodeRepository;

    public function __construct(VerifyController $controller, MobileCode $mobileCode, User $actor, $params = [])
    {
        $this->controller = $controller;
        $this->mobileCode = $mobileCode;
        $this->actor = $actor;
        $this->params = $params;
    }

    public function handle(Client $apiClient, Dispatcher $bus, UserValidator $validator, MobileCodeRepository $mobileCodeRepository)
    {
        $this->apiClient = $apiClient;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->mobileCodeRepository = $mobileCodeRepository;

        return call_user_func([$this, $this->mobileCode->type]);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function login()
    {
        if (!is_null($this->mobileCode->user)) {
            $this->controller->serializer = TokenSerializer::class;
            $params = [
                'username' => $this->mobileCode->user->username,
                'password' => ''
            ];

            return $this->bus->dispatch(new GenJwtToken($params));
        }

        throw new PermissionDeniedException;
    }

    protected function bind()
    {
        $this->controller->serializer = UserSerializer::class;
        if ($this->actor->exists) {
            $this->actor->changeMobile($this->mobileCode->mobile);
            $this->actor->changeMobileActive(User::MOBILE_ACTIVE);
            $this->actor->save();
            $this->mobileCode->user = $this->actor;
        }
        return $this->mobileCode->user;
    }

    protected function lostpwd()
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

    protected function verify()
    {
        $this->controller->serializer = UserSerializer::class;
        return $this->mobileCode->user;
    }

    protected function rebind()
    {
        $verify = $this->mobileCodeRepository->getSmsCode($this->actor->getOriginal('mobile'), 'verify', 1);

        if($verify && $verify->expired_at < Carbon::now()) {
            return $this->bind();
        }

        throw new SmsCodeVerifyException();
    }
}
