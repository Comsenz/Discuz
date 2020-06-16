<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountReplySerializer;
use App\Repositories\WechatOffiaccountReplyRepository;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class OffIAccountReplyDeleteController extends AbstractDeleteController
{
    public $serializer = OffIAccountReplySerializer::class;

    /**
     * @var WechatOffiaccountReplyRepository
     */
    protected $reply;

    public function __construct(WechatOffiaccountReplyRepository $reply)
    {
        $this->reply = $reply;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    protected function delete(ServerRequestInterface $request)
    {
        return $this->reply->findWellDelete(Arr::get($request->getQueryParams(), 'id', 0));
    }
}
