<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountReplySerializer;
use App\Repositories\WechatOffiaccountReplyRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class OffIAccountReplyResourceController extends AbstractResourceController
{
    public $serializer = OffIAccountReplySerializer::class;

    public $optionalInclude = [];

    /**
     * @var WechatOffiaccountReplyRepository
     */
    protected $reply;

    public function __construct(WechatOffiaccountReplyRepository $reply)
    {
        $this->reply = $reply;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id', 0);

        return $this->reply->findOrFail($id);
    }
}
