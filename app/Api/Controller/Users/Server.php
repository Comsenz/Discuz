<?php

namespace App\Api\Controller\Users;

use App\Passport\Repositories\ClientRepository;
use App\Passport\Repositories\ScopeRepository;
use App\Passport\Repositories\AccessTokenRepository;
use League\OAuth2\Server\CryptKey;
class Server 
{
    public $server;

    public function __construct(){
        // 初始化存储库
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
     
        // 私钥与加密密钥
        $privateKey = 'file://D:/phpStudy/WWW/discuz/private.key';
        // dd(decoct(fileperms($privateKey) & 0777));
        // $privateKey = new CryptKey('file:///private.key', 'passphrase'); // 如果私钥文件有密码
        $encryptionKey = '106BktSzs0W4OvGV3S/SCsWe2WmmW+s9LusUtBdrhjc='; // 加密密钥字符串
        // $encryptionKey = Key::loadFromAsciiSafeString($encryptionKey); //如果通过 generate-defuse-key 脚本生成的字符串，可使用此方法传入

        // 初始化 server
        $server = new \League\OAuth2\Server\AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptionKey
        );
       
        // dd($server);
        $this->server=$server;
    }
    
}

