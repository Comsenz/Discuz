<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\GenJwtToken;
use App\Exceptions\NoUserException;
use App\Models\SessionToken;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Contracts\Socialite\Factory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

abstract class AbstractWechatUserController extends AbstractResourceController
{
    protected $socialite;

    protected $bus;

    protected $cache;

    protected $validation;

    public function __construct(Factory $socialite, Dispatcher $bus, Repository $cache, ValidationFactory $validation)
    {
        $this->socialite = $socialite;
        $this->bus = $bus;
        $this->cache = $cache;
        $this->validation = $validation;
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
        $sessionId = Arr::get($request->getQueryParams(), 'sessionId');

        $request = $request->withAttribute('session', new SessionToken())->withAttribute('sessionId', $sessionId);

        $this->validation->make([
            'code' => Arr::get($request->getQueryParams(), 'code'),
            'sessionId' => Arr::get($request->getQueryParams(), 'sessionId'),
        ], [
            'code' => 'required',
            'sessionId' => 'required'
        ])->validate();

        $this->socialite->setRequest($request);

        $driver = $this->socialite->driver($this->getDriver());

        $user = $driver->user();

        $actor = $request->getAttribute('actor');

        $wechatUser = UserWechat::where($this->getType(), $user->getId())->orWhere('unionid', Arr::get($user->getRaw(), 'unionid'))->first();

        if ($wechatUser && $wechatUser->user) {
            //创建 token
            $params = [
                'username' => $wechatUser->user->username,
                'password' => ''
            ];

            $data = $this->fixData($user->getRaw(), $actor);
            unset($data['user_id']);
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

        if (!$wechatUser) {
            $wechatUser = new UserWechat();
        }
        $wechatUser->setRawAttributes($this->fixData($rawUser, $actor));
        $wechatUser->save();

        if ($actor->id) {
            $this->serializer = UserProfileSerializer::class;
            return $actor;
        }

        $token = SessionToken::generate($this->getDriver(), $rawUser);
        $token->save();
        throw (new NoUserException())->setToken($token);
    }

    abstract protected function getDriver();

    abstract protected function getType();

    protected function fixData($rawUser, $actor)
    {
        $data = array_merge($rawUser, ['user_id' => $actor->id, $this->getType() => $rawUser['openid']]);
        unset($data['openid'], $data['language']);
        $data['privilege'] = serialize($data['privilege']);
        return $data;
    }
}
