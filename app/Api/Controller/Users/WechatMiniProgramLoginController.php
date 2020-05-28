<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterWechatMiniProgramUser;
use App\Events\Users\Logind;
use App\Models\UserWechat;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Socialite\Exception\SocialiteException;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatMiniProgramLoginController extends AbstractResourceController
{
    use AssertPermissionTrait;

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
     * @throws SocialiteException
     * @throws InvalidConfigException
     * @throws DecryptException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);
        $js_code = Arr::get($attributes, 'js_code');
        $this->validation->make(
            ['js_code' => $js_code],
            ['js_code' => 'required']
        )->validate();

        $app = $this->easyWeChat::miniProgram([
            'app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
            'secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram'),
        ]);
        //获取小程序登陆session key
        $authSession = $app->auth->session($js_code);
        if (isset($authSession['errcode']) && $authSession['errcode'] != 0) {
            throw new SocialiteException($authSession['errmsg'], $authSession['errcode']);
        }
        //获取小程序用户信息
        /** @var UserWechat $wechatUser */
        $wechatUser = UserWechat::firstOrNew(['unionid' => Arr::get($authSession, 'unionid')]);
        if (Arr::get($attributes, 'iv') && Arr::get($attributes, 'encryptedData')) {
            $decryptedData = $app->encryptor->decryptData(
                $authSession['session_key'],
                Arr::get($attributes, 'iv'),
                Arr::get($attributes, 'encryptedData')
            );
            $wechatUser->min_openid = Arr::get($authSession, 'openid');
            $wechatUser->nickname = $decryptedData['nickName'];
            $wechatUser->city = $decryptedData['city'];
            $wechatUser->province = $decryptedData['province'];
            $wechatUser->country = $decryptedData['country'];
            $wechatUser->sex = $decryptedData['gender'];
            $wechatUser->headimgurl = $decryptedData['avatarUrl'];
        }

        if ($wechatUser->user_id) {
            //已绑定的用户登陆
            $user = $wechatUser->user;
        } else {
            //未绑定的用户注册
            $this->validation->make(
                [
                    'iv' => Arr::get($attributes, 'iv', ''),
                    'encryptedData' => Arr::get($attributes, 'encryptedData', '')
                ],
                [
                    'iv' => 'required',
                    'encryptedData' => 'required'
                ]
            )->validate();
            $this->assertPermission((bool)$this->settings->get('register_close'));

            $data['code'] = Arr::get($attributes, 'code');
            $data['username'] = $wechatUser->nickname;
            $data['register_ip'] = ip($request->getServerParams());
            $user = $this->bus->dispatch(
                new RegisterWechatMiniProgramUser($request->getAttribute('actor'), $data)
            );
            $wechatUser->user_id = $user->id;
        }
        $wechatUser->save();

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
