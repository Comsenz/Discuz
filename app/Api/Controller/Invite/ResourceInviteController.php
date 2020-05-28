<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Repositories\InviteRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceInviteController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = InviteSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user', 'group', 'group.permission'];

    /**
     * @var InviteRepository
     */
    protected $invite;

    /**
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

        return $this->invite->query()
            ->with(['group.permission' => function ($query) {
                $query->where('permission', 'not like', 'category%');
            }])
            ->where('code', $code)
            ->firstOrFail();
    }
}
