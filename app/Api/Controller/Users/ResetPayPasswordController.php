<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Models\SessionToken;
use Discuz\Http\DiscuzResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Validation\Factory as Validator;

class ResetPayPasswordController implements RequestHandlerInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = $request->getAttribute('actor');
        $pay_password = $request->getParsedBody()->get('pay_password', '');

        $this->validator->make(compact('pay_password'), [
            'pay_password' => [
                'bail',
                'required',
                'digits:6',
                function ($attribute, $value, $fail) use ($actor) {
                    // 验证支付密码
                    if (! $actor->checkWalletPayPassword($value)) {
                        $fail(trans('trade.wallet_pay_password_error'));
                    }
                }
            ],
        ])->validate();

        $token = SessionToken::generate('reset_pay_password', null, $actor->id);
        $token->save();

        return DiscuzResponseFactory::JsonResponse([
            'token' => $token->token,
            'userId' => $actor->id
        ]);
    }
}
