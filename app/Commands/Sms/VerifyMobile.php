<?php


namespace App\Commands\Sms;


use App\Api\Controller\Mobile\VerifyController;
use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Models\MobileCode;
use App\Models\User;
use Discuz\Api\Client;
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

    protected function login() {
        if(!is_null($this->mobileCode->user))
        {
            $this->controller->serializer = TokenSerializer::class;
            $params = [
                'username' => $this->mobileCode->user->username,
                'password' => ''
            ];
            $response = $this->bus->dispatch(new GenJwtToken($params));
            if($response->getStatusCode() === 200) {
                $this->mobileCode->state = 1;
                $this->mobileCode->save();
            }

            return $response;
        }
    }

    protected function bind()
    {
//        if(is_null($this->mobileCode->user)) {
//            $this->mobileCode->user->mobile = $this->mobileCode->mobile;
//            $this->mobileCode->user->mobile_confirmed = 1;
//            $this->mobileCode->user->save();
//        }
    }
}
