<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterUser;
use App\Commands\Users\RegisterWechatMiniProgramUser;
use App\Events\Users\Logind;
use App\Models\UserWechat;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractResourceController;
use EasyWeChat\Factory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatMiniProgramLoginController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    protected $easyWeChat;

    protected $bus;

    protected $cache;

    protected $validation;

    protected $events;

    protected $settings;

    public function __construct(Factory $easyWeChat, Dispatcher $bus, Repository $cache, ValidationFactory $validation, Events $events, SettingsRepository $settings)
    {
        $this->easyWeChat = $easyWeChat;
        $this->bus = $bus;
        $this->cache = $cache;
        $this->validation = $validation;
        $this->events = $events;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $js_code = Arr::get($request->getQueryParams(), 'js_code');
        $userInfo = Arr::get($request->getQueryParams(), 'userInfo');

        $this->validation->make([
            'js_code' => $js_code,
            'userInfo' => $userInfo,
        ], [
            'js_code' => 'required',
            'userInfo' => 'required'
        ])->validate();

        $app = $this->easyWeChat::miniProgram([
            'app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
            'secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram'),
        ]);
        $authSession = $app->auth->session($js_code);
        $wechatUser = UserWechat::where('unionid', Arr::get($authSession, 'unionid'))->first();

        $user = $wechatUser->user;
        if ($wechatUser && $wechatUser->user) {
            //登陆
            goto Login;
        } else {
            //注册
            $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);
            $attributes['register_ip'] = ip($request->getServerParams());

            $user = $this->bus->dispatch(
                new RegisterWechatMiniProgramUser($request->getAttribute('actor'), $attributes)
            );
        }
        Login:
        //创建 token
        $params = [
            'username' => $user->username,
            'password' => ''
        ];

        $response = $this->bus->dispatch(
            new GenJwtToken($params)
        );

        if ($response->getStatusCode() === 200) {
            $this->events->dispatch(new Logind($user));
        }

        return json_decode($response->getBody());
    }
}
