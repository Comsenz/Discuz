<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListUserWalletLogController.php xxx 2019-10-22 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use App\Api\Serializer\WalletUserSerializer;
use App\Commands\Wallet\UserWallet;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUserWalletLogController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = WalletUserSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        //订单编号
        $user_id = Arr::get($request->getQueryParams(), 'user_id');
        return $this->bus->dispatch(
            new UserWallet($user_id, $actor, $request->getParsedBody())
        );
    }
}
