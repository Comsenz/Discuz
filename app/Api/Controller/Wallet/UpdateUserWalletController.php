<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletSerializer;
use App\Commands\Wallet\UpdateUserWallet;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateUserWalletController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user'
    ];

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
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        //钱包ID
        $user_id = (int)Arr::get($request->getQueryParams(), 'user_id');
        return $this->bus->dispatch(
            new UpdateUserWallet($user_id, $actor, $request->getParsedBody())
        );
    }
}
