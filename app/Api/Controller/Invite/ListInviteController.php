<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Repositories\InviteRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListInviteController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = InviteSerializer::class;

    /**
     * @var InviteRepository
     */
    protected $inviteRepository;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param InviteRepository $inviteRepository
     * @param UrlGenerator $url
     */
    public function __construct(InviteRepository $inviteRepository, UrlGenerator $url)
    {
        $this->inviteRepository = $inviteRepository;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertAdmin($actor);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $status = Arr::get($filter, 'status', '');

        $query = $this->inviteRepository->query()
                    ->where('user_id', $actor->id)
                    ->where('type', 2)
                    ->when($status !== '', function (Builder $query) use ($status) {
                        $query->where('status', $status);
                    });

        $count = $limit > 0 ? $query->count() : null;

        $list = $query->skip($offset)->take($limit)->orderBy('id', 'desc')->get();

        $document->addPaginationLinks(
            $this->url->route('invite.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $count
        );

        $document->setMeta([
            'total' => $count,
            'pageCount' => ceil($count / $limit),
        ]);

        return $list;
    }
}
