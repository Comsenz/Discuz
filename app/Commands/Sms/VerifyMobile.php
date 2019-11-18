<?php


namespace App\Commands\Sms;


use App\Api\Controller\Mobile\VerifyController;
use App\Api\Controller\Oauth2\AccessTokenController;
use App\Api\Serializer\TokenSerializer;
use Discuz\Api\Client;

class VerifyMobile
{

    protected $controller;
    protected $mobileCode;
    protected $apiClient;

    public function __construct(VerifyController $controller, $mobileCode)
    {
        $this->controller = $controller;
        $this->mobileCode = $mobileCode;
    }

    public function handle(Client $apiClient)
    {
        $this->apiClient = $apiClient;

        return $this->{$this->mobileCode->type}();
    }

    protected function login() {
        if(!is_null($this->mobileCode->user))
        {
            $this->controller->serializer = TokenSerializer::class;
            $param = [
                'grant_type' => 'password',
                'client_id' => '',
                'client_secret' => '',
                'scope' => '',
                'username' => $this->mobileCode->user->username,
                'password' => ''
            ];

            $response = $this->apiClient->send(AccessTokenController::class, null, [], $param);
            if($response->getStatusCode() === 200) {
                $this->mobileCode->state = 1;
                $this->mobileCode->save();
            }
            return json_decode((string)$response->getBody());
        }
    }
}
