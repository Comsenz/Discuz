<?php

namespace App\Oauth;


use App\Passport\Repositories\UserRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use Illuminate\Support\Arr;

class Password extends Server
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function  __construct($actor,$request,$username,$password)
    {
        $this->actor=$actor;
        $this->password=$password;
        $this->username=$username;
        //处理请求
        $data = $request->getParsedBody();
        $user =[
            'grant_type'=>'password',
            'client_id'=> '1',
            'client_secret'=>'secret2',
            'scope'=>'',
            'username'=>$this->username,
            'password'=>$this->password
        ];
        $request= $request->withParsedBody($user);
        $this->request=$request;
        $this->actor=$actor;
        $this->password=$password;
        parent::__construct();
        $userRepository = new UserRepository(); // instance of UserRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $this->grant = new \League\OAuth2\Server\Grant\PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

    }

}