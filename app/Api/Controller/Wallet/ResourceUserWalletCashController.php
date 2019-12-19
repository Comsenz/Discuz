<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ResourceCashUserWalletController.php xxx 2019-10-22 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletCashSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Repositories\UserWalletCashRepository;

class ResourceUserWalletCashController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletCashSerializer::class;

    /**
     * @var UserWalletCashRepository
     */
    protected $cash;

    /**
     * @param UserWalletCashRepository $cash [description]
     */
    public function __construct(UserWalletCashRepository $cash)
    {
        $this->cash = $cash;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');
        return $this->cash->findCashOrFail($id);
    }
}
