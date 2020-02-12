<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\DialogSerializer;
use App\Models\User;
use App\Repositories\DialogRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListDialogController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = DialogSerializer::class;

    /**
     * @var DialogRepository
     */
    protected $dialog;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $dialogCount;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['user'];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param DialogRepository $dialog
     * @param UrlGenerator $url
     */
    public function __construct(DialogRepository $dialog, UrlGenerator $url)
    {
        $this->dialog = $dialog;
        $this->url = $url;
    }

    /**
     * 我的关注
     * {@inheritdoc}
     * @throws InvalidParameterException
     * @throws NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $userFollow = $this->search($actor, $filter, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('dialog.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogCount
        );

        $document->setMeta([
            'total' => $this->dialogCount,
            'size' => $limit,
        ]);

        return $userFollow;
    }

    /**
     * @param User $actor
     * @param array $filter
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search(User $actor, $filter, $limit = null, $offset = 0)
    {
        $join_field = '';
        $query = $this->dialog->query();



        $this->dialogCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
