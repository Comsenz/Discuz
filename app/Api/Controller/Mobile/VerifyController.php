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

use App\Api\Serializer\VerifyMobileSerializer;
use App\Commands\Sms\VerifyMobile;
use App\Exceptions\SmsCodeVerifyException;
use App\Models\MobileCode;
use App\Repositories\MobileCodeRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class VerifyController extends AbstractResourceController
{
    public $serializer = VerifyMobileSerializer::class;

    protected $mobileCodeRepository;

    protected $bus;

    protected $validation;

    public function __construct(MobileCodeRepository $mobileCodeRepository, Dispatcher $bus, Factory $validation)
    {
        $this->mobileCodeRepository = $mobileCodeRepository;
        $this->bus = $bus;
        $this->validation = $validation;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws SmsCodeVerifyException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $type = Arr::get($data, 'type');

        if ($type === 'verify' || $type === 'reset_pay_pwd') {
            $data['mobile'] = $actor->getRawOriginal('mobile');
        }

        $mobile = Arr::get($data, 'mobile');
        $code = Arr::get($data, 'code');

        $data['sms_type'] = $type;
        $data['sms_code'] = $code;

        $this->validation->make($data, [
            'mobile' => 'required',
            'sms_type' => 'required',
            'sms_code' => 'required'
        ])->validate();

        /**
         * @var MobileCode $mobileCode
        **/
        $mobileCode = $this->mobileCodeRepository->getSmsCode($mobile, $type);

        if (!$mobileCode || $mobileCode->code !== $code || $mobileCode->expired_at < Carbon::now()) {
            throw new SmsCodeVerifyException();
        }

        $mobileCode->changeState(MobileCode::USED_STATE);
        $mobileCode->save();

        $data['ip'] = ip($request->getServerParams());
        $data['port'] = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);

        //各种类型验证通过后，返回相关数据
        return $this->bus->dispatch(new VerifyMobile($this, $mobileCode, $actor, $data));
    }
}
