<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogMessageSerializer;
use App\Models\User;
use App\Repositories\DialogMessageRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListDialogMessageController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = DialogMessageSerializer::class;

    /**
     * @var DialogMessageRepository
     */
    protected $dialogMessage;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $dialogMessageCount;

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
     * @param DialogMessageRepository $dialogMessage
     * @param UrlGenerator $url
     */
    public function __construct(DialogMessageRepository $dialogMessage, UrlGenerator $url)
    {
        $this->dialogMessage = $dialogMessage;
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
            $this->url->route('dialog.message.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogMessageCount
        );

        $document->setMeta([
            'total' => $this->dialogMessageCount,
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
        $query = $this->dialogMessage->query();

        $query->where('dialog_id', $filter['dialog_id']);

        $query->join('dialog', 'dialog.id', '=', 'dialog_message.dialog_id')
            ->where(function ($query) use ($actor) {
                $query->where('sender_user_id', $actor->id);
                $query->orWhere('recipient_user_id', $actor->id);
            });

        $this->dialogMessageCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
