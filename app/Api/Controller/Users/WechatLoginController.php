<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\LocationSerializer;
use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\GenJwtToken;
use App\Exceptions\NoUserException;
use App\Models\User;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Contracts\Socialite\Factory;

class WechatLoginController extends AbstractResourceController
{
    protected $socialite;
    protected $bus;
    protected $cache;

    public function __construct(Factory $socialite, Dispatcher $bus, Repository $cache)
    {
        $this->socialite = $socialite;
        $this->bus = $bus;
        $this->cache = $cache;
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

        $request = $request->withAttribute('cache', $this->cache);

        $this->socialite->setRequest($request);

        $driver = $this->socialite->driver('wechat');

        if(!Arr::get($request->getQueryParams(), 'code')) {
            $response = $driver->redirect();
            $this->serializer = LocationSerializer::class;
            return ['location' => $response->getHeaderLine('location')];
        }

        $user = $driver->user();

        $state = Arr::get($request->getQueryParams(), 'state');

        $actor = User::find($state);

        $wechatUser = UserWechat::where('openid', $user->id)->first();

        $this->wechatSaved($user);

        if(!$wechatUser) {
            if($actor->id) {
                $user->user['user_id'] = $actor->id;
            }
            UserWechat::create($user->user);
            return $actor;
        }

        if($wechatUser->user) {
            //创建 token
            $params = [
                'username' => $wechatUser->user->username,
                'password' => ''
            ];

            return $this->bus->dispatch(new GenJwtToken($params));
        }

        if($actor->id) {
            $wechatUser->user_id = $actor->id;
            $wechatUser->save();
            return $actor;
        }

        $this->error($user);
    }

    protected function wechatSaved($user)
    {
        UserWechat::saved(function() use ($user) {
            if(isset($user['user_id'])) {
                $this->serializer = UserProfileSerializer::class;
            } else {
                $this->error($user);
            }
        });
    }

    /**
     * @param $user
     * @throws NoUserException
     */
    private function error($user) {
        throw (new NoUserException())->setUser($user->user);
    }
}
