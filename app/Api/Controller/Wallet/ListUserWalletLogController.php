<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListUserWalletLogController.php xxx 2019-10-22 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\WalletUserSerializer;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUserWalletLogController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = WalletUserSerializer::class;

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

        //订单编号
        $user_id = Arr::get($request->getQueryParams(), 'user_id');
        return $this->bus->dispatch(
            //new UserWallet($user_id, $actor, $request->getParsedBody())
        );
    }
}
