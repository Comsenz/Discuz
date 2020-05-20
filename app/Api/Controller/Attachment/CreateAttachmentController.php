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
        $type = (int) Arr::get($request->getParsedBody(), 'type', 0);
        $order = (int) Arr::get($request->getParsedBody(), 'order', 0);
        $ipAddress = ip($request->getServerParams());

        /**
         * TODO: is_gallery is_sound 需要整合为 type 字段
         *
         * type：0 附件 1 图片 2 音频 3 视频
         */
        $isGallery = (bool) Arr::get($request->getParsedBody(), 'isGallery', false);
        $type = $isGallery ? 1 : $type;

        return $this->bus->dispatch(
            new CreateAttachment($actor, $file, $ipAddress, $type, $order)
        );
    }
}
