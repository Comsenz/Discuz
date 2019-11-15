<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListCashUserWalletController.php xxx 2019-11-10 157:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletCashSerializer;
use App\Commands\Wallet\ListCashUserWallet;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListCashUserWalletController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletCashSerializer::class;
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
        return $this->bus->dispatch(
            new ListCashUserWallet($actor, $request->getQueryParams())
        );
    }
}
