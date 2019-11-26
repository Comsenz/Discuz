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
use App\Commands\Wallet\ReasourseUserWalletCash;

class ResourceUserWalletCashController extends AbstractResourceController
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
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        return $this->bus->dispatch(
            new ReasourseUserWalletCash($actor)
        );

    }
}
