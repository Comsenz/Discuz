<?php


namespace App\Commands\Sms;


use App\Api\Controller\Mobile\VerifyController;
use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserSerializer;
use App\Commands\Users\GenJwtToken;
use App\Models\MobileCode;
use App\Models\User;
use Discuz\Api\Client;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;

class VerifyMobile
{

    protected $controller;
    protected $mobileCode;
    protected $apiClient;
    protected $actor;
    protected $bus;

    public function __construct(VerifyController $controller, MobileCode $mobileCode, User $actor)
    {
        $this->controller = $controller;
        $this->mobileCode = $mobileCode;
        $this->actor = $actor;
    }

    public function handle(Client $apiClient, Dispatcher $bus)
    {
        $this->apiClient = $apiClient;
        $this->bus = $bus;

        return $this->{$this->mobileCode->type}();
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function login() {
        if(!is_null($this->mobileCode->user))
        {
            $this->controller->serializer = TokenSerializer::class;
            $params = [
                'username' => $this->mobileCode->user->username,
                'password' => ''
            ];

            return $this->bus->dispatch(new GenJwtToken($params));
        }

        throw new PermissionDeniedException;
    }

    protected function bind() {
        $this->controller->serializer = UserSerializer::class;
        return $this->mobileCode->user;
    }

    protected function lostpwd()
    {
        $this->controller->serializer = UserSerializer::class;
        return $this->mobileCode->user;
    }
}
