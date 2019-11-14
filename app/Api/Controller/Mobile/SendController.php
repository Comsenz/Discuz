<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Mobile;

use App\Api\Serializer\SmsSendSerializer;
use App\Exceptions\IntervalSmsSend;
use App\Models\MobileCode;
use App\SmsMessages\SendCodeMessage;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class SendController extends AbstractCreateController
{

    public $serializer = SmsSendSerializer::class;

    use QcloudTrait;

    const CODE_EXCEPTION = 30; //单位：分钟
    const CODE_INTERVAL = 60; //单位：秒

    protected $validation;
    protected $cache;

    public function __construct(ValidationFactory $validation, CacheRepository $cache)
    {
        $this->validation = $validation;
        $this->cache = $cache;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws \Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {

        $data = Arr::get($request->getParsedBody(), 'data.attributes');

        $this->validation->make($data, [
            'mobile' => 'required',
            'type' => 'required'
        ])->validate();

        $mobile = Arr::get($data, 'mobile');
        $type = Arr::get($data, 'type');

        if(!is_null($this->cache->get($mobile, null))) {
            throw new IntervalSmsSend();
        }

        $mobileCode = MobileCode::make($mobile, self::CODE_EXCEPTION, $type);

        $result = $this->smsSend($mobile, new SendCodeMessage(['code' => $mobileCode->code, 'expire' => self::CODE_EXCEPTION]));

        if(isset($result['qcloud']['status']) && $result['qcloud']['status'] === 'success') {
            $this->cache->put($mobile, 'send', Carbon::now()->addSeconds(self::CODE_INTERVAL));
            $mobileCode->save();
        }

        return ['interval' => self::CODE_INTERVAL];
    }
}
