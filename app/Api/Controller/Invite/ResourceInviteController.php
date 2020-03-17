<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Models\Invite;
use App\Repositories\InviteRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceInviteController extends AbstractResourceController
{
    protected $invite;

    /**
     * {@inheritdoc}
     */
    public $serializer = InviteSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['group'];

    /**
     * ResourceInviteController constructor.
     * @param InviteRepository $invite
     */
    public function __construct(InviteRepository $invite)
    {
        $this->invite = $invite;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $code = Arr::get($request->getQueryParams(), 'code');

        $query = $this->invite->query()->where(['code' => $code])->first();

//        $query->load('group', 'group.permission');

//        dd($query->toArray());

        return $query;
    }
}
