<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
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

    public $include = ['users'];

    public function __construct(UserRepository $users, Dispatcher $bus, Application $app, Validator $validator)
    {
        $this->users = $users;
        $this->bus = $bus;
        $this->app = $app;
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = Arr::get($request->getParsedBody(), 'data.attributes');

        $validator = $this->validator->make($data, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->bus->dispatch(
            new GenJwtToken($data)
        );
    }
}
