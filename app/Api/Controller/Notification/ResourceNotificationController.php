<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ResourceNotificationController.php 28830 2019-10-12 15:46 yanchen $
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationSerializer;
use App\Models\StopWord;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceNotificationController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = NotificationSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {

        $actor = $request->getAttribute('actor');
        $notificationId = Arr::get($request->getQueryParams(), 'id');

        $notification = $actor->notifications->where('id', $notificationId)->first();
        $notification && $notification->markAsRead();

        return $notification;
    }
}