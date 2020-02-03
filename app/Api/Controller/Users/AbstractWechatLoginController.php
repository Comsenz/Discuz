<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\LocationSerializer;
use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\GenJwtToken;
use App\Exceptions\NoUserException;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Contracts\Socialite\Factory;

abstract class AbstractWechatLoginController extends AbstractResourceController
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

        $sessionId = Arr::get($request->getQueryParams(), 'sessionId', Str::random());

        $request = $request->withAttribute('cache', $this->cache)->withAttribute('sessionId', $sessionId);

        $this->socialite->setRequest($request);

        $driver = $this->socialite->driver($this->getDriver());

        if (!Arr::get($request->getQueryParams(), 'code')) {
            $response = $driver->redirect();
            $this->serializer = LocationSerializer::class;
            return ['location' => $response->getHeaderLine('location')];
        }

        $user = $driver->user();

        $actor = $request->getAttribute('actor');

        $wechatUser = UserWechat::where($this->getType(), $user->getId())->orWhere('unionid', Arr::get($user->getRaw(), 'unionid'))->first();

        if ($wechatUser && $wechatUser->user) {
            //创建 token
            $params = [
                'username' => $wechatUser->user->username,
                'password' => ''
            ];

            $wechatUser->setKeyName($this->getType());
            $data = $this->fixData($user->getRaw(), $actor);
            unset($data['user_id'], $data[$this->getType()], $data['unionid']);
            $wechatUser->setRawAttributes($data);
            $wechatUser->save();

            return $this->bus->dispatch(new GenJwtToken($params));
        }

        $this->error($user, $actor, $wechatUser);
    }

    /**
     * @param $user
     * @param $actor
     * @param UserWechat $wechatUser
     * @return mixed
     * @throws NoUserException
     */
    private function error($user, $actor, $wechatUser)
    {
        $rawUser = $user->getRaw();

        if(!$wechatUser) {
            $wechatUser = new UserWechat();
        }
        $wechatUser->setKeyName($this->getType());
        $wechatUser->setRawAttributes($this->fixData($rawUser, $actor));
        $wechatUser->save();

        if ($actor->id) {
            $this->serializer = UserProfileSerializer::class;
            return $actor;
        }

        throw (new NoUserException())->setUser($rawUser);
    }

    abstract protected function getDriver();

    abstract protected function getType();

    protected function fixData($rawUser, $actor) {
        $data = array_merge($rawUser, ['user_id' => $actor->id, $this->getType() => $rawUser['openid']]);
        unset($data['openid'], $data['language']);
        $data['privilege'] = serialize($data['privilege']);
        return $data;
    }
}
