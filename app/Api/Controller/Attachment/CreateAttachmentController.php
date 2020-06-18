<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Attachment;

use App\Api\Serializer\AttachmentSerializer;
use App\Commands\Attachment\CreateAttachment;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateAttachmentController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = AttachmentSerializer::class;

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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $file = Arr::get($request->getUploadedFiles(), 'file');
        $name = Arr::get($request->getParsedBody(), 'name', '');
        $type = (int) Arr::get($request->getParsedBody(), 'type', 0);
        $order = (int) Arr::get($request->getParsedBody(), 'order', 0);
        $ipAddress = ip($request->getServerParams());

        return $this->bus->dispatch(
            new CreateAttachment($actor, $file, $name, $ipAddress, $type, $order)
        );
    }
}
