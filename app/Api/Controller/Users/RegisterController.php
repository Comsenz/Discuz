<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterUser;
use App\MessageTemplate\Wechat\WechatRegisterMessage;
use App\Notifications\System;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Passport\Repositories\UserRepository as CurrentUser;

class RegisterController extends AbstractCreateController
{
    use AssertPermissionTrait;

    protected $bus;

    protected $users;

    protected $settings;

    protected $app;

    public function __construct(Dispatcher $bus, UserRepository $users, SettingsRepository $settings, Application $app)
    {
        $this->bus = $bus;
        $this->users = $users;
        $this->settings = $settings;
        $this->app = $app;
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
        $attributes['register_ip'] = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $this->bus->dispatch(
            new RegisterUser($request->getAttribute('actor'), $attributes)
        );

        unset($attributes['password']);

        $result = $this->bus->dispatch(new GenJwtToken($attributes));

        // 在注册绑定微信后 发送注册微信通知
        $this->app->make(CurrentUser::class)->getUser()->notify(new System(WechatRegisterMessage::class));

        return $result;
    }
}
