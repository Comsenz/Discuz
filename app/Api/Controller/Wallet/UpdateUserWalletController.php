<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UpdateUserWalletController.php xxx 2019-10-22 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        //钱包ID
        $wallet_id = Arr::get($request->getQueryParams(), 'wallet_id');
        return $this->bus->dispatch(
            new UpdateUserWallet($wallet_id, $actor, $request->getParsedBody())
        );
    }
}
