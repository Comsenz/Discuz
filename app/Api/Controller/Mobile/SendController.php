<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Controller\Mobile;

use App\Api\Serializer\SmsSendSerializer;
use App\Models\MobileCode;
use App\Repositories\MobileCodeRepository;
use App\Rules\Captcha;
use App\SmsMessages\SendCodeMessage;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class SendController extends AbstractCreateController
{
    use QcloudTrait;

    const CODE_EXCEPTION = 5; //单位：分钟

    const CODE_INTERVAL = 60; //单位：秒

    public $serializer = SmsSendSerializer::class;

    protected $validation;

    protected $cache;

    protected $mobileCodeRepository;

    protected $settings;

    protected $type = [
        'login',
        'bind',
        'rebind',
        'reset_pwd',
        'reset_pay_pwd',
        'verify',
    ];

    public function __construct(
        ValidationFactory $validation,
        CacheRepository $cache,
        MobileCodeRepository $mobileCodeRepository,
        SettingsRepository $settings
    ) {
        $this->validation = $validation;
        $this->cache = $cache;
        $this->mobileCodeRepository = $mobileCodeRepository;
        $this->settings = $settings;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws \Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data.attributes');

        $type = Arr::get($data, 'type');

        // 直接使用用户手机号
        if ($type === 'verify' || $type === 'reset_pay_pwd') {
            $data['mobile'] = $actor->getRawOriginal('mobile');
        }

        // 手机号验证规则
        if (!(bool)$this->settings->get('qcloud_sms', 'qcloud')) {
            // 未开启短息服务不发送短信
            $mobileRule = [
                function ($attribute, $value, $fail) {
                    $fail('短信服务未开启。');
                },
            ];
        } elseif ($type == 'bind') {
            // 判断手机号是否已经被绑定
            if ($actor->mobile) {
                throw new \Exception('mobile_is_already_bind');
            }

            $mobileRule = 'required|unique:users,mobile';
        } elseif ($type == 'rebind') {
            // 如果是重新绑定，需要在验证旧手机后 10 分钟内
            $unverified = MobileCode::where('mobile', $actor->getRawOriginal('mobile'))
                ->where('type', 'verify')
                ->where('state', 1)
                ->where('updated_at', '<', Carbon::now()->addMinutes(10))
                ->doesntExist();

            $mobileRule = [
                function ($attribute, $value, $fail) use ($actor, $unverified) {
                    if ($unverified) {
                        $fail('请验证旧的手机号。');
                    } elseif ($value == $actor->getRawOriginal('mobile')) {
                        $fail('请输入新的手机号。');
                    }
                },
                'required',
                'unique:users,mobile',
            ];
        } elseif (in_array($type, ['reset_pwd', 'reset_pay_pwd'])) {
            // 如果已经绑定，不能再发送绑定短息
            // 如果重设密码，必须要已绑定的手机号
            $mobileRule = 'required|exists:users,mobile';
        } else {
            $mobileRule = 'required';
        }

        // 验证码
        $data['captcha'] = [
            Arr::pull($data, 'captcha_ticket', ''),
            Arr::pull($data, 'captcha_rand_str', ''),
            ip($request->getServerParams()),
        ];

        $this->validation->make($data, [
            'captcha' => [new Captcha],
            'mobile' => $mobileRule,
            'type' => 'required|in:' . implode(',', $this->type),
        ])->validate();

        $mobileCode = $this->mobileCodeRepository->getSmsCode($data['mobile'], $type);

        $ip = ip($request->getServerParams());

        if (!is_null($mobileCode) && $mobileCode->exists) {
            $mobileCode = $mobileCode->refrecode(self::CODE_EXCEPTION, $ip);
        } else {
            $mobileCode = MobileCode::make($data['mobile'], self::CODE_EXCEPTION, $type, $ip);
        }

        $result = $this->smsSend($data['mobile'], new SendCodeMessage(['code' => $mobileCode->code, 'expire' => self::CODE_EXCEPTION]));

        if (isset($result['qcloud']['status']) && $result['qcloud']['status'] === 'success') {
            $mobileCode->save();
        }

        return ['interval' => self::CODE_INTERVAL];
    }
}
