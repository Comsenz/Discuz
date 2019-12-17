<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: RegisterController.php xxx 2019-11-15 11:03:00 LiuDongdong $
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterUser;
use App\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class RegisterController extends AbstractCreateController
{
    use AssertPermissionTrait;

    protected $bus;
    protected $users;
    protected $settings;

    public function __construct(Dispatcher $bus, UserRepository $users, SettingsRepository $settings)
    {
        $this->bus = $bus;
        $this->users = $users;
        $this->settings = $settings;
    }

    public $serializer = TokenSerializer::class;

    /**
     * {@inheritdoc}
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {

        $this->assertPermission((bool)$this->settings->get('register_close'));

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR');
        $attributes['register_ip'] = $ip;

        $this->bus->dispatch(
            new RegisterUser($request->getAttribute('actor'), $attributes)
        );

        unset($attributes['password']);

        return $this->bus->dispatch(new GenJwtToken($attributes));
    }
}
