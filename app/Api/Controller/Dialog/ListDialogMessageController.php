<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogMessageSerializer;
use App\Models\User;
use App\Repositories\DialogMessageRepository;
use App\Repositories\DialogRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
     * @var DialogRepository
     */
    protected $dialogs;

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
     * @var ValidationFactory
     */
    public $validation;


    /**
     * @var string[]
     */
    public $sortFields = [
        'createdAt',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['user','user.groups'];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param DialogRepository $dialogs
     * @param DialogMessageRepository $dialogMessage
     * @param ValidationFactory $validation
     * @param UrlGenerator $url
     */
    public function __construct(DialogRepository $dialogs, DialogMessageRepository $dialogMessage, ValidationFactory $validation, UrlGenerator $url)
    {
        $this->dialogs = $dialogs;
        $this->dialogMessage = $dialogMessage;
        $this->validation = $validation;
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
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $this->validation->make(
            ['dialog_id' => Arr::get($filter, 'dialog_id')],
            ['dialog_id' => 'required']
        )->validate();

        //设置登录用户已读
        $dialog = $this->dialogs->findOrFail($filter['dialog_id'], $actor);
        if ($dialog->sender_user_id == $actor->id) {
            $type = 'sender';
        } else {
            $type = 'recipient';
        }
        $dialog->setRead($type);

        $dialogMessages = $this->search($actor, $sort, $filter, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('dialog.message.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogMessageCount
        );

        $dialogMessages->loadMissing($include);

        $document->setMeta([
            'total' => $this->dialogMessageCount,
            'pageCount' => ceil($this->dialogMessageCount / $limit),
        ]);

        return $dialogMessages;
    }

    /**
     * @param User $actor
     * @param array $filter
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search(User $actor, $sort, $filter, $limit = null, $offset = 0)
    {
        $query = $this->dialogMessage->query();

        $query->select('dialog_message.*');
        $query->where('dialog_id', $filter['dialog_id']);

        $query->join('dialog', 'dialog.id', '=', 'dialog_message.dialog_id')
            ->where(function ($query) use ($actor) {
                $query->where('sender_user_id', $actor->id);
                $query->orWhere('recipient_user_id', $actor->id);
            });

        $this->dialogMessageCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        return $query->get();
    }
}
