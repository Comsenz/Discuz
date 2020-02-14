<?php


namespace App\Api\Controller\Notification;


use App\Api\Serializer\NotificationTplSerializer;
use App\Models\NotificationTpl;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListNotificationTplController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = NotificationTplSerializer::class;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return NotificationTpl[]|\Illuminate\Database\Eloquent\Collection|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        return NotificationTpl::all();
    }
}
