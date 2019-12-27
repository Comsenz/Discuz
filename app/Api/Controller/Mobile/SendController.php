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
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class SendController extends AbstractCreateController
{
    public $serializer = SmsSendSerializer::class;

    use QcloudTrait;

    const CODE_EXCEPTION = 5; //单位：分钟

    const CODE_INTERVAL = 60; //单位：秒

    protected $validation;

    protected $cache;

    protected $mobileCodeRepository;

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

        if ($type === 'verify') {
            $data['mobile'] = $actor->getOriginal('mobile');
        }

        // 如果已经绑定，不能再发送绑定短息
        $this->validation->make($data, [
            'mobile' => in_array($type, ['bind', 'rebind']) ? 'required|unique:users,mobile' : 'required',
            'type' => 'required'
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
