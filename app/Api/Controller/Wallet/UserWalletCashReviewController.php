<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UserWalletCashReviewController.php xxx 2019-11-10 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Discuz\Api\JsonApiResponse;
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
        $ip_address = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
        $result = $this->bus->dispatch(
            new UserWalletCashReview($actor, $request->getParsedBody(), $ip_address)
        );
        $data = [
            'type' => 'cash-review',
            'data' => [
                'result' => $result
            ],
        ];
        return new JsonApiResponse($data);
    }
}
