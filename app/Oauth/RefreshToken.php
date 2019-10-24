<?php

namespace App\Oauth;


use App\Passport\Repositories\RefreshTokenRepository;

class RefreshToken extends Server
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function  __construct($actor,$request)
    {
        $this->actor=$actor;
        //处理请求
        $data=$request->getqueryParams();
        $request= $request->withParsedBody($data);
        $this->request=$request;
        $this->actor=$actor;
        parent::__construct();
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $this->grant = new \League\OAuth2\Server\Grant\RefreshTokenGrant($refreshTokenRepository);



    }

}