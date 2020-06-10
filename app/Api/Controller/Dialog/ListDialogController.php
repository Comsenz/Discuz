<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogSerializer;
use App\Models\User;
use App\Repositories\DialogRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
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

    public $sortFields = [
        'dialogMessageId',
        'createdAt',
    ];
    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['sender','recipient','dialogMessage','sender.groups','recipient.groups'];

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

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $dialogs = $this->search($actor, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('dialog.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogCount
        );

        $dialogs->loadMissing($include);

        $document->setMeta([
            'total' => $this->dialogCount,
            'pageCount' => ceil($this->dialogCount / $limit),
        ]);

        return $dialogs;
    }

    /**
     * @param User $actor
     * @param null $limit
     * @param int $offset
     * @param $sort
     * @return Collection
     */
    public function search(User $actor, $sort, $limit = null, $offset = 0)
    {
        $query = $this->dialog->query();

        $query->where('sender_user_id', $actor->id);
        $query->orWhere('recipient_user_id', $actor->id);

        $this->dialogCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        return $query->get();
    }
}
