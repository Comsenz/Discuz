<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Mobile;

use App\Api\Serializer\SmsSendSerializer;
use App\Models\MobileCode;
use App\Repositories\MobileCodeRepository;
use App\SmsMessages\SendCodeMessage;
use Discuz\Api\Controller\AbstractCreateController;
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

    protected $type = [
        'login',
        'bind',
        'rebind',
        'reset_pwd',
        'reset_pay_pwd',
        'verify',
    ];

    public function __construct(ValidationFactory $validation, CacheRepository $cache, MobileCodeRepository $mobileCodeRepository)
    {
        $this->validation = $validation;
        $this->cache = $cache;
        $this->mobileCodeRepository = $mobileCodeRepository;
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
            $data['mobile'] = $actor->getOriginal('mobile');
        }

        // 手机号验证规则
        if ($type == 'bind') {
            // 判断手机号是否已经被绑定
            if ($actor->mobile) {
                throw new \Exception('mobile_is_already_bind');
            }

            $mobileRule = 'required|unique:users,mobile';
        } elseif ($type == 'rebind') {
            // 如果是重新绑定，需要在验证旧手机后 10 分钟内
            $unverified = MobileCode::where('mobile', $actor->getOriginal('mobile'))
                ->where('type', 'verify')
                ->where('state', 1)
                ->where('updated_at', '<', Carbon::now()->addMinutes(10))
                ->doesntExist();

            $mobileRule = [
                function ($attribute, $value, $fail) use ($actor, $unverified) {
                    if ($unverified) {
                        $fail('请验证旧的手机号。');
                    } elseif ($value == $actor->getOriginal('mobile')) {
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

        $this->validation->make($data, [
            'mobile' => $mobileRule,
            'type' => 'required|in:' . implode(',', $this->type),
        ])->validate();

        $mobileCode = $this->mobileCodeRepository->getSmsCode($data['mobile'], $type);

        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR');

        if (!is_null($mobileCode) && $mobileCode->exists) {
            $mobileCode = $mobileCode->refrecode(self::CODE_EXCEPTION, $ip);
        } else {
            $mobileCode = MobileCode::make($data['mobile'], self::CODE_EXCEPTION, $type, $ip);
        }

        $result = $this->smsSend($data['mobile'], new SendCodeMessage(['code' => $mobileCode->code, 'expire' => self::CODE_EXCEPTION]));

        if (isset($result['qcloud']['status']) && $result['qcloud']['status'] === 'success') {
            $this->cache->put($data['mobile'], 'send', Carbon::now()->addSeconds(self::CODE_INTERVAL));
            $mobileCode->save();
        }

        return ['interval' => self::CODE_INTERVAL];
    }
}
