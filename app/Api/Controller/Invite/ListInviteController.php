<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListInviteController.php 28830 2019-10-12 15:46 chenkeke $
 */

namespace App\Api\Controller\Invite;


use App\Api\Serializer\InviteSerializer;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListInviteController extends AbstractListController
{
    public $serializer = InviteSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        $res = $this->bus->dispatch(
            new CreateThread('aaa','bb',array('cc'))
        );
        dd($res);
        return $res;
    }
}