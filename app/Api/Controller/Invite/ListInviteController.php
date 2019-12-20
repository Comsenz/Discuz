<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListInviteController.php 28830 2019-11-19 15:46 yanchen $
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Models\Invite;
use App\Repositories\InviteRepository;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListInviteController extends AbstractListController
{
    public $serializer = InviteSerializer::class;
    protected $InviteRepository;

    public function __construct(InviteRepository $InviteRepository)
    {
        $this->InviteRepository = $InviteRepository;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        return $this->InviteRepository->getAdminCodeList($actor);
    }
}
