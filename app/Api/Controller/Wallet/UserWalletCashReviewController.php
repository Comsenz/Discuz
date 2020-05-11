<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wallet;

use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Commands\Wallet\UserWalletCashReview;

class UserWalletCashReviewController implements RequestHandlerInterface
{
    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        // 获取请求的IP
        $ip_address = ip($request->getServerParams());
        $result = $this->bus->dispatch(
            new UserWalletCashReview($actor, $request->getParsedBody(), $ip_address)
        );
        $data = [
            'type' => 'cash-review',
            'data' => [
                'result' => $result
            ],
        ];
        return DiscuzResponseFactory::JsonApiResponse($data);
    }
}
