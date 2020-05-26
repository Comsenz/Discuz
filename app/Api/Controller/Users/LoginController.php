<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Events\Users\Logind;
use App\Passport\Repositories\UserRepository;
use App\User\Bind;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class LoginController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    protected $users;

    protected $bus;

    protected $app;

    protected $validator;

    protected $events;

    protected $bind;

    public $include = ['users'];

    public function __construct(UserRepository $users, Dispatcher $bus, Application $app, Validator $validator, Events $events, Bind $bind)
    {
        $this->users = $users;
        $this->bus = $bus;
        $this->app = $app;
        $this->validator = $validator;
        $this->events = $events;
        $this->bind = $bind;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $this->validator->make($data, [
            'username' => 'required',
            'password' => 'required',
        ])->validate();

        $response = $this->bus->dispatch(
            new GenJwtToken($data)
        );

        if ($response->getStatusCode() === 200) {
            $user = $this->app->make(UserRepository::class)->getUser();

            if ($token = Arr::get($data, 'token')) {
                $this->bind->wechat($token, $user);
            }

            if ($mobile = Arr::get($data, 'mobile')) {
                $this->bind->mobile($token);
            }

            $this->events->dispatch(new Logind($user));

        }
        return json_decode($response->getBody());
    }
}
