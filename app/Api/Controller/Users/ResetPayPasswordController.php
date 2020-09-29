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

namespace App\Api\Controller\Users;

use App\Api\Serializer\SessionSerializer;
use App\Models\SessionToken;
use App\Models\UserWalletFailLogs;
use App\Repositories\UserWalletFailLogsRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\Factory as Validator;
use Tobscure\JsonApi\Document;

class ResetPayPasswordController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = SessionSerializer::class;

    /**
     * @var Validator
     */
    protected $validator;

    protected $userWalletFailLogs;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator, UserWalletFailLogsRepository $userWalletFailLogs)
    {
        $this->validator = $validator;
        $this->userWalletFailLogs = $userWalletFailLogs;
    }

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        //验证错误次数
        $failCount = $this->userWalletFailLogs->getCountByUserId($actor->id);
        if ($failCount > UserWalletFailLogs::TOPLIMIT) {
            throw new \Exception('pay_password_failures_times_toplimit');
        }

        $pay_password = Arr::get($request->getParsedBody(), 'data.attributes.pay_password');

        $this->validator->make(compact('pay_password'), [
            'pay_password' => [
                'bail',
                'required',
                'digits:6',
                function ($attribute, $value, $fail) use ($actor,$request,$failCount) {
                    // 验证支付密码
                    if (! $actor->checkWalletPayPassword($value)) {
                        //记录钱包密码错误日志
                        UserWalletFailLogs::build(ip($request->getServerParams()), $actor->id);

                        if (UserWalletFailLogs::TOPLIMIT == $failCount) {
                            throw new \Exception('pay_password_failures_times_toplimit');
                        } else {
                            $fail(trans('trade.wallet_pay_password_error', ['value'=>UserWalletFailLogs::TOPLIMIT - $failCount]));
                        }
                    }
                }
            ],
        ])->validate();

        // 正确后清除错误记录
        if ($failCount > 0) {
            UserWalletFailLogs::deleteAll($actor->id);
        }

        $token = SessionToken::generate('reset_pay_password', null, $actor->id);
        $token->save();

        return [
            'sessionId' => $token->token
        ];
    }
}
