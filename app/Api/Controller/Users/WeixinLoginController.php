<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Users;

use App\Api\Controller\Oauth2\AccessTokenController;
use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Exceptions\NoUserException;
use App\Models\UserWechat;
use App\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Contracts\Socialite\Factory;

class WeixinLoginController extends AbstractResourceController
{
    protected $socialite;
    protected $bus;


    public function __construct(Factory $socialite, Dispatcher $bus)
    {
        $this->socialite = $socialite;
        $this->bus = $bus;
    }

    public $serializer = TokenSerializer::class;


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws NoUserException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->socialite->setRequest($request);

        $user = $this->socialite->driver('weixin')->user();

        $weixinUser = UserWechat::where('openid', $user->id)->first();

        if(!$weixinUser) {
            $user->user['privilege'] = serialize($user->user['privilege']);
            UserWechat::create($user->user);

            $this->error($user);
        }

        if($weixinUser->user) {
            //创建 token
            $params = [
                'username' => $weixinUser->user->username,
                'password' => ''
            ];

            return $this->bus->dispatch(new GenJwtToken($params));
        }

        $this->error($user);
    }

    /**
     * @param $user
     * @throws NoUserException
     */
    private function error($user) {
        $e = new NoUserException();
        $e->setUser($user->user);
        throw $e;
    }
}
