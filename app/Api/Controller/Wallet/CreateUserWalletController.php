<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateUserWalletController.php xxx 2019-10-24 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use App\Api\Serializer\UserWalletSerializer;
use App\Commands\Wallet\CreateUserWallet;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateUserWalletController extends AbstractResourceController
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
        return $this->bus->dispatch(
            new CreateUserWallet($actor, $request->getParsedBody())
        );
    }
}
