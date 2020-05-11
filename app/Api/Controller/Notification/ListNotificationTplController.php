<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationTplSerializer;
use App\Models\NotificationTpl;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListNotificationTplController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = NotificationTplSerializer::class;

    protected $tpl;

    public function __construct(NotificationTpl $tpl)
    {
        $this->tpl = $tpl;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $type = Arr::get($request->getQueryParams(), 'type', 0);

        $tpl = $this->tpl->where('type', $type)->get();

        return $tpl;
    }
}
