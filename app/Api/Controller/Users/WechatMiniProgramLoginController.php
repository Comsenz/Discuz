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
use Discuz\Socialite\Exception\SocialiteException;
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
     * @throws SocialiteException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $js_code = Arr::get($attributes, 'js_code');
        $this->validation->make(['js_code' => $js_code], ['js_code' => 'required'])->validate();

        $app = $this->easyWeChat::miniProgram([
            'app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
            'secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram'),
        ]);
        $authSession = $app->auth->session($js_code);

        if (isset($authSession['errcode']) && $authSession['errcode'] != 0) {
            throw new SocialiteException($authSession['errmsg'], $authSession['errcode']);
        }

        $wechatUser = UserWechat::where('unionid', Arr::get($authSession, 'unionid'))
                                ->orWhere('min_openid', Arr::get($authSession, 'openid'))->first();

        if ($wechatUser && $wechatUser->user) {
            //登陆
            if (!$wechatUser->min_openid) {
                //绑定之前unionid绑定的公众号账号
                $wechatUser->min_openid = Arr::get($authSession, 'openid');
                $wechatUser->save();
            }

            $user = $wechatUser->user;
        } else {
            //注册
            if (!$wechatUser) {
                $this->validation->make(
                    ['iv' => Arr::get($attributes, 'iv'),
                     'encryptedData' => Arr::get($attributes, 'encryptedData')],
                    ['iv' => 'required',
                     'encryptedData' => 'required']
                )->validate();
                $decryptedData = $app->encryptor->decryptData(
                    $authSession['session_key'],
                    Arr::get($attributes, 'iv'),
                    Arr::get($attributes, 'encryptedData')
                );
                $decryptedData['min_openid'] = $decryptedData['openId'];
                $decryptedData['nickname'] = $decryptedData['nickName'];
                $decryptedData['sex'] = $decryptedData['gender'];
                $decryptedData['headimgurl'] = $decryptedData['avatarUrl'];

                $wechatUser = UserWechat::build(Arr::Only(
                    $decryptedData,
                    ['min_openid', 'nickname', 'city', 'province', 'country', 'sex',  'headimgurl',  'unionId',  'sex',  'sex', ]
                ));
                $wechatUser->save();
            }

            $data['username'] = $wechatUser->nickname;
            $data['register_ip'] = ip($request->getServerParams());
            $user = $this->bus->dispatch(
                new RegisterWechatMiniProgramUser($request->getAttribute('actor'), $data)
            );

            $wechatUser->user_id = $user->id;
            $wechatUser->save();
        }

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
