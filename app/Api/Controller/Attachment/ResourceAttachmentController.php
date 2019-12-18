<?php

namespace App\Api\Controller\Attachment;

use App\Api\Serializer\AttachmentSerializer;
use App\Exceptions\OrderException;
use App\Models\Order;
use App\Repositories\AttachmentRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceAttachmentController extends AbstractResourceController
{
    /**
     * @var AttachmentRepository
     */
    protected $attachments;

    /**
     * {@inheritdoc}
     */
    public $serializer = AttachmentSerializer::class;

    /**
     * @param AttachmentRepository $attachments
     */
    public function __construct(AttachmentRepository $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * {@inheritdoc}
     * @throws OrderException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $attachmentId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        $attachment = $this->attachments->findOrFail($attachmentId, $actor);

        // 帖子是否被删除
        $post = $attachment->post;
        if ($post->deleted_at && ! $actor->isAdmin()) {
            return null;
        }

        // 主题是否收费
        $thread = $post->thread;
        if ($thread->price > 0 && ! $actor->isAdmin()) {
            $order = Order::where('user_id', $actor->id)
                ->where('thread_id', $thread->id)
                ->where('type', Order::ORDER_TYPE_REWARD)
                ->where('status', Order::ORDER_STATUS_PAID)
                ->exists();

            if (! $order) {
                throw new OrderException('order_post_not_found');
            }
        }

        return $attachment;
    }
}
