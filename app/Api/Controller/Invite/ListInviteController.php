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
use App\Models\Group;
use App\Models\Invite;
use App\Models\User;
use App\Repositories\ClassifyRepository;
use App\Searchs\Classify\ClassifySearch;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListInviteController extends AbstractListController
{
    public $serializer = InviteSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $actor->id = 1;

        return Invite::where([
            ['user_id', '=', 1],
            ['type', '=', '2']])->get();
    }
}
